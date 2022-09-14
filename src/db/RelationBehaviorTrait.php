<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;

/**
 * Trait RelationBehaviorTrait
 *
 * @property array $relations = []
 *
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait RelationBehaviorTrait
{
    /**
     * @return array|array[]
     * @inheritdoc
     */
    public function relationBehaviors(): array
    {
        if (empty($this->relations)) {
            return [];
        }
        $relations = [];
        $relationsIndexKeys = [];
        foreach ($this->relations as $relation => $indexKeys) {
            if (is_int($relation)) {
                $relations[] = $indexKeys;
            } else {
                $relations[] = $relation;
                $relationsIndexKeys[$relation] = $indexKeys;
            }
        }
        return [
            'relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => $relations,
                'indexKeys' => $relationsIndexKeys,
            ],
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => $relations,
            ]
        ];
    }
}