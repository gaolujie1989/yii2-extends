<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman;

use lujie\workerman\db\Command;
use lujie\workerman\log\Logger;
use lujie\workerman\web\ErrorHandler;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http;
use Workerman\WebServer;
use Workerman\Worker;
use Yii;
use yii\db\Connection;
use yii\web\Application;
use yii\web\UploadedFile;

/**
 * Class Yii2WebServer
 * @package lujie\workerman
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Yii2WebServer extends WebServer
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
     * @var int
     */
    public $maxRequestCount = 10000;

    /**
     * @var int
     */
    private $currentRequestCount = 0;

    /**
     * Run webserver instance.
     *
     * @see Workerman.Worker::run()
     */
    public function run(): void
    {
        $this->uploadTmpDir = rtrim($this->uploadTmpDir, '/') . '/';
        parent::run();
    }

    /**
     * @throws \Exception
     * @inheritdoc
     */
    public function onWorkerStart(): void
    {
        $this->initYii2Apps();
        XHProfiler::init();
        parent::onWorkerStart();
    }

    /**
     * @inheritdoc
     */
    protected function initYii2Apps(): void
    {
        include_once __DIR__ . '/rewrite_functions.php';
        include_once __DIR__ . '/rewrite_classes.php';
        $this->yii2Apps = [];
        foreach ($this->serverRoot as $domain => $serverConfig) {
            $domainRoot = $this->serverRoot[$domain]['root'];
            $workermanCwd = getcwd();
            chdir($domainRoot);

            $_SERVER['SCRIPT_NAME'] = '/index.php';
            $_SERVER['SCRIPT_FILENAME'] = $domainRoot . $_SERVER['SCRIPT_NAME'];
            $appFile = rtrim($serverConfig['root'], '/') . '/' . $this->yii2AppFile;
            $this->yii2Apps[$domain] = $yii2App = include $appFile;
            foreach ($yii2App->getComponents() as $name => $config) {
                if ($name === 'logger') {
                    $config['class'] = Logger::class;
                    $yii2App->setComponents([$name => $config]);
                }
                if ($name === 'errorHandler') {
                    $config['class'] = ErrorHandler::class;
                    $yii2App->setComponents([$name => $config]);
                }
                if (ltrim($config['class'], '\\') === Connection::class) {
                    $config['commandClass'] = Command::class;
                    $yii2App->setComponents([$name => $config]);
                }
                $yii2App->get($name);
            }
            foreach ($yii2App->getModules() as $name => $config) {
                $yii2App->getModule($name);
            }
            $yii2App->getErrorHandler()->register();
            chdir($workermanCwd);
        }
    }

    /**
     * @param TcpConnection $connection
     * @throws \yii\base\ErrorException
     * @inheritdoc
     */
    public function onMessage($connection): void
    {
        if ($this->maxRequestCount && ++$this->currentRequestCount > $this->maxRequestCount) {
            Worker::stopAll();
        }

        // REQUEST_URI.
        $workerman_url_info = parse_url('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        if (!$workerman_url_info) {
            Http::header('HTTP/1.1 400 Bad Request');
            $connection->close('<h1>400 Bad Request</h1>');
            return;
        }

        $workerman_path = $workerman_url_info['path'] ?? '/';
        if ($this->isYii2AppUrl($workerman_path)) {
            $_SERVER['REMOTE_ADDR'] = $connection->getRemoteIp();
            $_SERVER['REMOTE_PORT'] = $connection->getRemotePort();
            $content = $this->runYii2App();
            if (strtolower($_SERVER['HTTP_CONNECTION']) === 'keep-alive') {
                $connection->send($content);
            } else {
                $connection->close($content);
            }
        } else {
            parent::onMessage($connection);
        }
    }

    /**
     * @param string $urlPath
     * @return bool
     * @inheritdoc
     */
    protected function isYii2AppUrl(string $urlPath): bool
    {
        foreach ($this->yii2AppUrlPrefix as $urlPrefix) {
            if (strpos($urlPrefix, '!') === 0) {
                $urlPrefix = substr($urlPrefix, 1);
                if (strpos($urlPath, $urlPrefix) === 0) {
                    return false;
                }
            } else if (strpos($urlPath, $urlPrefix) === 0) {
                return true;
            }
        }
        return $this->yii2AppUrlDefault;
    }

    /**
     * @return string
     * @throws \yii\base\ErrorException
     * @inheritdoc
     */
    protected function runYii2App(): string
    {
        $yii2App = $this->yii2Apps[$_SERVER['SERVER_NAME']] ?? current($this->yii2Apps);
        $workermanSiteConfig = $this->serverRoot[$_SERVER['SERVER_NAME']] ?? current($this->serverRoot);
        $domainRoot = rtrim($workermanSiteConfig['root'], '/');
        $workermanCwd = getcwd();
        chdir($domainRoot);
        ob_start();
        // Try to run yii2 app.
        try {
            $_SERVER['REQUEST_TIME'] = time();
            $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
            $_SERVER['SCRIPT_NAME'] = '/index.php';
            $_SERVER['SCRIPT_FILENAME'] = $domainRoot . $_SERVER['SCRIPT_NAME'];
            $this->setUploadedFiles();
            $componentsConfig = $yii2App->getComponents();
            $yii2App->set('request', $componentsConfig['request']);
            $yii2App->set('response', $componentsConfig['response']);
            $yii2App->getRequest()->setRawBody($GLOBALS['HTTP_RAW_POST_DATA']);
            XHProfiler::start();
            $yii2App->run();
        } catch (\Exception $exception) {
            if ($exception->getMessage() !== 'jump_exit') {
                $yii2App->getErrorHandler()->handleException($exception);
            }
        } catch (\Error $error) {
            $yii2App->getErrorHandler()->handleException($error);
        } finally {
            XHProfiler::end();
        }
        $content = ob_get_clean();
        Yii::getLogger()->flush(true);
        XHProfiler::save();
        chdir($workermanCwd);
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
}
