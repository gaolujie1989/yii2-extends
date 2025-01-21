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
    use TraceableBehaviorTrait;
    use RelationBehaviorTrait, RelationExtraFieldsTrait, AliasBehaviorTrait, AliasFieldTrait, AliasErrorsTrait;
    use SaveTrait, UpdateTrait, DeleteTrait, TransactionTrait, DbConnectionTrait;

    public $relations;

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = array_merge(parent::behaviors(), $this->traceableBehaviors(), $this->relationBehaviors());
        if (str_ends_with(static::class, 'Form')) {
            $behaviors = array_merge($behaviors, $this->aliasBehaviors());
        }
        return $behaviors;
    }

    /**
     * @return string|null
     * @inheritdoc
     */
    public function optimisticLock(): ?string
    {
        if ($this->hasAttribute('version')) {
            return 'version';
        }
        return parent::optimisticLock();
    }
}
