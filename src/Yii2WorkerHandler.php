<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman;

use lujie\workerman\db\Command;
use lujie\workerman\db\Connection;
use lujie\workerman\log\Logger;
use lujie\workerman\web\ErrorHandler;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http;
use Workerman\Worker;
use Yii;
use yii\base\Application;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\db\Connection as YiiConnection;
use yii\web\UploadedFile;

class Yii2WorkerHandler
{
    /**
     * @var string
     */
    public $yii2AppFile = 'app.php';

    /**
     * @var Application[]
     */
    public $yii2Apps = [];

    /**
     * @var array
     */
    public $yii2AppUrlPrefix = [
        '!/uploads',
    ];

    public $yii2AppUrlDefault = true;

    /**
     * @var string
     */
    public $uploadTmpDir = '/tmp';

    /**
     * Virtual host to path mapping.
     *
     * @var array ['workerman.net'=>['root' => '/home'], 'www.workerman.net'=>['root' => 'home/www']]
     */
    public $serverRoot = array();

    #region Restart after max request

    /**
     * @var int
     */
    private $currentRequestCount = 0;

    /**
     * @var int
     */
    public $maxRequestCount = 10000;

    /**
     * @inheritdoc
     */
    protected function checkMaxRequestCount(): void
    {
        if ($this->maxRequestCount && ++$this->currentRequestCount > $this->maxRequestCount) {
            Worker::stopAll();
        }
    }

    #endregion

    #region onWorkerStart

    /**
     * @throws InvalidConfigException
     */
    public function initYii2Apps(): void
    {
        include_once __DIR__ . '/rewrite_functions.php';
        include_once __DIR__ . '/rewrite_classes.php';
        $this->yii2Apps = [];
        foreach ($this->serverRoot as $domain => $serverConfig) {
            $domainRoot = is_string($serverConfig) ? $serverConfig : $serverConfig['root'];
            $workermanCwd = getcwd();
            chdir($domainRoot);
            $appFile = rtrim($domainRoot, '/') . '/' . $this->yii2AppFile;
            $this->yii2Apps[$domain] = ($yii2App = include $appFile);
            $this->adaptYii2App($yii2App);
            chdir($workermanCwd);
        }
    }

    /**
     * @param Application $app
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function adaptYii2App(Application $app): void
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = getcwd() . $_SERVER['SCRIPT_NAME'];
        foreach ($app->getComponents() as $name => $config) {
            if ($name === 'logger') {
                $config['class'] = Logger::class;
                $app->setComponents([$name => $config]);
            }
            if ($name === 'errorHandler') {
                $config['class'] = ErrorHandler::class;
                $app->setComponents([$name => $config]);
            }
            if (ltrim($config['class'], '\\') === YiiConnection::class) {
                $config['class'] = Connection::class;
                $config['commandClass'] = Command::class;
                $app->setComponents([$name => $config]);
            }
            $app->get($name);
        }
        foreach ($app->getModules() as $name => $config) {
            $app->getModule($name);
        }
        $app->getErrorHandler()->register();
    }

    #endregion

    #region onMessage

    /**
     * @param TcpConnection $connection
     * @return bool
     * @throws ErrorException
     * @inheritdoc
     */
    public function handleMessage(TcpConnection $connection, Http\Request $request): bool
    {
        $this->checkMaxRequestCount();

        // REQUEST_URI.
        $urlInfo = parse_url('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        if (!$urlInfo) {
            Http::header('HTTP/1.1 400 Bad Request');
            $connection->close('<h1>400 Bad Request</h1>');
            return true;
        }

        $urlPath = $urlInfo['path'] ?? '/';
        if ($this->isYii2AppUrl($urlPath)) {
            $_SERVER['REMOTE_ADDR'] = $connection->getRemoteIp();
            $_SERVER['REMOTE_PORT'] = $connection->getRemotePort();
            $yii2App = $this->yii2Apps[$_SERVER['SERVER_NAME']] ?? current($this->yii2Apps);
            $serverConfig = $this->serverRoot[$_SERVER['SERVER_NAME']] ?? current($this->serverRoot);
            $domainRoot = is_string($serverConfig) ? $serverConfig : $serverConfig['root'];
            $workermanCwd = getcwd();
            chdir($domainRoot);
            $GLOBALS['HTTP_RAW_POST_DATA'] = $request->rawBody();
            $content = $this->handleYii2AppRequest($yii2App);
            chdir($workermanCwd);
            if (strtolower($_SERVER['HTTP_CONNECTION']) === 'keep-alive') {
                $connection->send($content);
            } else {
                $connection->close($content);
            }
            return true;
        }
        return false;
    }

    /**
     * @param Application $app
     * @return string
     * @throws ErrorException
     * @inheritdoc
     */
    protected function handleYii2AppRequest(Application $app): string
    {
        ob_start();
        // Try to run yii2 app.
        try {
            XHProfiler::start();
            $_SERVER['REQUEST_TIME'] = time();
            $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
            $_SERVER['SCRIPT_NAME'] = '/index.php';
            $_SERVER['SCRIPT_FILENAME'] = getcwd() . $_SERVER['SCRIPT_NAME'];
            $this->setUploadedFiles();
            $componentsConfig = $app->getComponents();
            $app->set('request', $componentsConfig['request']);
            $app->set('response', $componentsConfig['response']);
            $app->getRequest()->setRawBody($GLOBALS['HTTP_RAW_POST_DATA']);
            $app->run();
        } catch (\Exception $exception) {
            if ($exception->getMessage() !== 'jump_exit') {
                $app->getErrorHandler()->handleException($exception);
            }
        } catch (\Throwable $error) {
            $app->getErrorHandler()->handleException($error);
        } finally {
            XHProfiler::end();
            XHProfiler::save();
        }
        $content = ob_get_clean();
        Yii::getLogger()->flush(true);
        return $content;
    }

    /**
     * @throws \Exception
     * @inheritdoc
     */
    protected function setUploadedFiles(): void
    {
        $uploadFiles = $_FILES;
        $_FILES = [];
        $this->uploadTmpDir = rtrim($this->uploadTmpDir, '/') . '/';
        foreach ($uploadFiles as $file) {
            $tmpName = $this->uploadTmpDir . WORKERMAN_UPLOAD_FILENAME_PREFIX
                . date('ymdHis') . '_' . random_int(1000, 9999);
            file_put_contents($tmpName, $file['file_data']);
            $_FILES[$file['name']] = [
                'name' => $file['file_name'],
                'type' => $file['file_type'] ?? '',
                'tmp_name' => $tmpName,
                'error' => UPLOAD_ERR_OK,
                'size' => $file['file_size'],
            ];
        }
        UploadedFile::reset();
    }

    /**
     * @param string $urlPath
     * @inheritdoc
     */
    public function isYii2AppUrl(string $urlPath): bool
    {
        foreach ($this->yii2AppUrlPrefix as $urlPrefix) {
            if (strpos($urlPrefix, '!') === 0) {
                $urlPrefix = substr($urlPrefix, 1);
                if (strpos($urlPath, $urlPrefix) === 0) {
                    return false;
                }
            } elseif (strpos($urlPath, $urlPrefix) === 0) {
                return true;
            }
        }
        return $this->yii2AppUrlDefault;
    }

    #endregion
}