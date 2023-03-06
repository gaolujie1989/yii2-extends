<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use Yii;
use yii\base\Application;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\ExitException;

/**
 * Class RestOptionRequestBehavior
 * @package lujie\extend\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RestOptionsRequestBehavior extends Behavior
{
    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'beforeRequest',
        ];
    }

    /**
     * @param Event $event
     * @throws ExitException
     * @inheritdoc
     */
    public function beforeRequest(Event $event): void
    {
        if (Yii::$app->getRequest()->getIsOptions()) {
            Yii::$app->end(200);
        }
    }
}
