<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use yii\db\BaseActiveRecord;

/**
 * Trait RelationBehaviorTrait
 *
 * @property array $relations = []
 * @property array $relationAttributeAlias = []
 * @property array $linkUnlinkRelations = []
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
                'relationAttributeAlias' => $this->relationAttributeAlias ?? [],
                'linkUnlinkRelations' => $this->linkUnlinkRelations ?? [],
            ],
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => $relations,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function resetRelationBehaviors(): void
    {
        $behaviors = $this->relationBehaviors();
        foreach ($behaviors as $behaviorName => $behavior) {
            $this->detachBehavior($behaviorName);
        }
        $this->attachBehaviors($behaviors);
    }

    /**
     * @param string $name
     * @param array|BaseActiveRecord $data
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function setRelation(string $name, array|BaseActiveRecord $data): void
    {
        /** @var RelationSavableBehavior $behavior */
        $behavior = $this->getBehavior('relationSave');
        $behavior->setRelation($name, $data);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getSavedRelations(string $name): array|BaseActiveRecord
    {
        /** @var RelationSavableBehavior $behavior */
        $behavior = $this->getBehavior('relationSave');
        return $behavior->getSavedRelations($name);
    }
}
