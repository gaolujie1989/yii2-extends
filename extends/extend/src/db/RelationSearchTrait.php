<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;

/**
 * Trait RelationSearchTrait
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait RelationSearchTrait
{
    public $relationsSearchKeys = ['attribute' => ['relation', 'attribute']];

    /**
     * @return array
     * @inheritdoc
     */
    protected function relationSearchRules(): array
    {
        /** @var BaseActiveRecord $this */
        return array_merge(ModelHelper::searchRules($this), [
            [['key'], 'string'],
        ]);
    }

    protected function searchRelations(BaseActiveRecord $model)
    {
        foreach ($this->relationsSearchKeys as $key => [$relation, $relationAttribute]) {
            $getter = 'get' . ucfirst($relation);
            /** @var ActiveQuery $activeQuery */
            $activeQuery = $model->{$getter}();
            $activeQuery->alias($relation);
        }
    }
}
