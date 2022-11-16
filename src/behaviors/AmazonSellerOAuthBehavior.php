<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\sp\behaviors;

use Yii;
use yii\authclient\AuthAction;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\base\Controller;

/**
 * Class AmazonSellerOAuthBehavior
 * @package lujie\amazon\sp\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AmazonSellerOAuthBehavior extends Behavior
{
    /**
     * @return string[]
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction'
        ];
    }

    /**
     * @param ActionEvent $event
     * @inheritdoc
     */
    public function beforeAction(ActionEvent $event): void
    {
        if ($event->action instanceof AuthAction) {
            $request = Yii::$app->getRequest();
            $code = $request->get('code');
            $spApiCode = $request->get('spapi_oauth_code');
            if ($code === null && $spApiCode !== null) {
                $request->setQueryParams(array_merge($request->getQueryParams(), ['code' => $spApiCode]));
            }
        }
    }
}