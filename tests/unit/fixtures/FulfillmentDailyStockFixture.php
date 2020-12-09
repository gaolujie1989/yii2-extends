<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\fixtures;


use lujie\fulfillment\models\FulfillmentDailyStock;
use yii\test\ActiveFixture;

/**
 * Class FulfillmentDailyStockMovementFixture
 * @package lujie\fulfillment\tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentDailyStockFixture extends ActiveFixture
{
    public $modelClass = FulfillmentDailyStock::class;
}