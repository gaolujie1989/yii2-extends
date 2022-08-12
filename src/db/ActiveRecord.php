<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use lujie\alias\behaviors\AliasBehaviorTrait;

/**
 * Class ActiveRecord
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    use RowPrepareTrait;
    use RelationClassTrait;
    use TraceableBehaviorTrait, AliasBehaviorTrait, AliasFieldTrait;
    use SaveTrait, DeleteTrait, TransactionTrait, DbConnectionTrait;
}