<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman;

use lujie\workerman\log\Logger;
use lujie\workerman\web\Response;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http;
use Workerman\WebServer;
use Workerman\Worker;
use Yii;
use yii\web\Application;

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
     * Used to save user OnWorkerStart callback settings.
     *
     * @var callback
     */
    protected $_onWorkerReload = null;

    /**
     * @var string
     */
    public $uploadTmpDir = '/tmp';

    /**
     * Run webserver instance.
     *
     * @see Workerman.Worker::run()
     */
    public function run(): void
    {
        $this->uploadTmpDir = rtrim($this->uploadTmpDir, '/') . '/';
        $this->_onWorkerReload = $this->onWorkerReload;
        $this->onWorkerReload  = array($this, 'onWorkerReload');
        parent::run();
    }

    /**
     * @throws \Exception
     * @inheritdoc
     */
    public function onWorkerStart(): void
    {
        $this->initYii2Apps();
        parent::onWorkerStart();
    }

    /**
     * @inheritdoc
     */
    public function onWorkerReload(): void
    {
        $this->initYii2Apps();

        // Try to emit onWorkerStart callback.
        if ($this->_onWorkerStart) {
            try {
                call_user_func($this->_onWorkerStart, $this);
            } catch (\Exception $e) {
                self::log($e);
                exit(250);
            } catch (\Error $e) {
                self::log($e);
                exit(250);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function initYii2Apps(): void
    {
        include_once __DIR__ . '/rewrite_upload_functions.php';
        $this->yii2Apps = [];
        foreach ($this->serverRoot as $domain => $config) {
            $appFile = rtrim($config['root'], '/') . '/' . $this->yii2AppFile;
            $this->yii2Apps[$domain] = include $appFile;
        }
        foreach ($this->yii2Apps as $yii2App) {
            foreach ($yii2App->getComponents() as $name => $config) {
                if ($name === 'response') {
                    $config['class'] = Response::class;
                    $yii2App->setComponents([$name => $config]);
                    continue;
                }
                if ($name === 'logger') {
                    $config['class'] = Logger::class;
                    $yii2App->setComponents([$name => $config]);
                }
                $yii2App->get($name);
            }
            foreach ($yii2App->getModules() as $name => $config) {
                $yii2App->get($name);
            }
        }
    }

    /**
     * @throws \Exception
     * @inheritdoc
     */
    public function setUploadedFiles(): void
    {
        $uploadFiles = $_FILES;
        $_FILES = [];
        foreach ($uploadFiles as $file) {
            $tmpName = $this->uploadTmpDir
                . implode('_', [WORKERMAN_UPLOAD_FILENAME_PREFIX, date('ymdHis'),  random_int(1000, 9999)]);
            $_FILES[$file['name']] = [
                'name' => $file['file_name'],
                'type' => $file['file_type'] ?? '',
                'tmp_name' => $tmpName,
                'error' => UPLOAD_ERR_OK,
                'size' => $file['file_size'],
            ];
        }
    }

    /**
     * @param TcpConnection $connection
     * @inheritdoc
     */
    public function onMessage($connection): void
    {
        // REQUEST_URI.
        $workerman_url_info = parse_url('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        if (!$workerman_url_info) {
            Http::header('HTTP/1.1 400 Bad Request');
            $connection->close('<h1>400 Bad Request</h1>');
            return;
        }

        $workerman_path = $workerman_url_info['path'] ?? '/';
        if ($this->isYii2AppUrl($workerman_path)) {
            $yii2App = clone $this->yii2Apps[$_SERVER['SERVER_NAME']] ?? current($this->yii2Apps);
            ob_start();
            // Try to run yii2 app.
            try {
                $_SERVER['REMOTE_ADDR'] = $connection->getRemoteIp();
                $_SERVER['REMOTE_PORT'] = $connection->getRemotePort();
                $this->setUploadedFiles();
                $yii2App->getRequest()->setRawBody($GLOBALS['HTTP_RAW_POST_DATA']);
                $yii2App->run();
                Yii::getLogger()->flush(true);
            } catch (\Throwable $e) {
                // Jump_exit?
                if ($e->getMessage() !== 'jump_exit') {
                    Worker::safeEcho($e);
                }
                echo $e->getMessage() . $e->getTraceAsString();
            }
            $content = ob_get_clean();
            unset($yii2App);
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
    public function isYii2AppUrl(string $urlPath): bool
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
}
