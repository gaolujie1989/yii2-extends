<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman;

use Workerman\Connection\TcpConnection;
use Workerman\WebServer;
use yii\base\InvalidConfigException;

/**
 * Class Yii2WebServer
 * @package lujie\workerman
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Yii2WebServer extends WebServer
{
    /**
     * @var Yii2WorkerHandler
     */
    public $yii2WorkerHandler;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function onWorkerStart(): void
    {
        if ($this->yii2WorkerHandler === null) {
            $this->yii2WorkerHandler = new Yii2WorkerHandler();
            $this->yii2WorkerHandler->serverRoot = $this->serverRoot;
        }
        $this->yii2WorkerHandler->initYii2Apps();
        parent::onWorkerStart();
    }

    /**
     * @param TcpConnection $connection
     * @throws \yii\base\ErrorException
     * @inheritdoc
     */
    public function onMessage(TcpConnection $connection): void
    {
        if ($this->yii2WorkerHandler->handleMessage($connection)) {
            return;
        }
        parent::onMessage($connection);
    }
}
