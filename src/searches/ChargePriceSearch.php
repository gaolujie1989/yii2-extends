<?php

namespace lujie\charging\searches;

use lujie\alias\behaviors\AliasPropertyBehavior;
use lujie\alias\behaviors\MoneyAliasBehavior;
use lujie\charging\models\ChargePrice;
use lujie\extend\db\AliasFieldTrait;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\SearchTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class ChargePrice
 * @package lujie\charging\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargePriceSearch extends ChargePrice
{
    use SearchTrait;
}
