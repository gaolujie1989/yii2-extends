<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\base\db;

use lujie\alias\behaviors\AliasBehaviorTrait;
use lujie\extend\db\AliasFieldTrait;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\DeleteTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;

/**
 * Class ActiveRecord
 * @package lujie\data\recording\base\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, AliasBehaviorTrait, AliasFieldTrait, SaveTrait, DeleteTrait, TransactionTrait, DbConnectionTrait;
}
