<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman;

use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http;
use Workerman\WebServer;
use Workerman\Worker;
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
     * @throws \Exception
     * @inheritdoc
     */
    public function onWorkerStart(): void
    {
        parent::onWorkerStart();
        $this->initYii2Apps();
    }

    /**
     * @inheritdoc
     */
    public function initYii2Apps(): void
    {
        foreach ($this->serverRoot as $domain => $config) {
            $appFile = rtrim($config['root'], '/') . '/' . $this->yii2AppFile;
            $this->yii2Apps[$domain] = include $appFile;
        }
        foreach ($this->yii2Apps as $yii2App) {
            foreach ($yii2App->getComponents() as $name => $config) {
                if (in_array($name, ['request', 'response'], true)) {
                    continue;
                }
                $component = $yii2App->get($name);
            }
            foreach ($yii2App->getModules() as $name => $config) {
                $module = $yii2App->get($name);
            }
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
                $yii2App->run();
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
