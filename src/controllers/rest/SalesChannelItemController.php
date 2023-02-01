<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\extend\rest\MethodAction;
use lujie\sales\channel\forms\SalesChannelItemPushForm;
use lujie\sales\channel\models\SalesChannelItem;

/**
 * Class SalesChannelOrderController
 * @package kiwi\sales\channel\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelItemController extends ActiveController
{
    public $modelClass = SalesChannelItem::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'push' => [
                'class' => MethodAction::class,
                'modelClass' => SalesChannelItemPushForm::class,
                'method' => 'push'
            ]
        ]);
    }
}