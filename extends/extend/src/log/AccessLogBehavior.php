<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\log;

use Yii;
use yii\base\ActionEvent;
use yii\base\Application;
use yii\base\Behavior;

/**
 * Class AccessLogBehavior
 * @package lujie\extend\log
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AccessLogBehavior extends Behavior
{
    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            Application::EVENT_BEFORE_ACTION => 'beforeAction'
        ];
    }

    /**
     * @param ActionEvent $event
     * @inheritdoc
     */
    public function beforeAction(ActionEvent $event)
    {
        $request = Yii::$app->getRequest();
        Yii::info("Access Action {$event->action->getUniqueId()} by IP: {$request->getUserIP()}, UA: {$request->getUserAgent()}", __METHOD__);
    }
}
