<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\controllers\rest;

use lujie\charging\models\ShippingTable;
use lujie\charging\searches\ShippingTableMatch;
use lujie\extend\rest\ActiveController;
use lujie\extend\rest\MethodAction;

/**
 * Class ShippingTableController
 * @package lujie\charging\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableController extends ActiveController
{
    public $modelClass = ShippingTable::class;

    public $matchClass = ShippingTableMatch::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'match' => [
                'class' => MethodAction::class,
                'modelClass' => $this->matchClass,
                'method' => 'match'
            ]
        ]);
    }
}
