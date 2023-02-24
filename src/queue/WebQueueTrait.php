<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\queue;

use yii\base\InvalidConfigException;
use yii\queue\cli\SignalLoop;
use yii\web\Application as WebApp;

/**
 * Class Queue with Web
 * @package lujie\extend\queue\web
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait WebQueueTrait
{
    /**
     * @param \yii\base\Application $app
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        if ($app instanceof WebApp) {
            $app->controllerMap[$this->getCommandId()] = [
                    'class' => $this->commandClass,
                    'queue' => $this,
                ] + $this->commandOptions;
        }
    }

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        if ($this->loopConfig === SignalLoop::class) {
            $this->loopConfig = ValueLoop::class;
        }
        parent::init();
    }
}
