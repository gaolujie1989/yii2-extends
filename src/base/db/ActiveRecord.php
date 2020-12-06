<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\base\db;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use lujie\fulfillment\models\FulfillmentAccountRelationTrait;

/**
 * Class ActiveRecord
 * @package lujie\fulfillment\base\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;
    use FulfillmentAccountRelationTrait;
}