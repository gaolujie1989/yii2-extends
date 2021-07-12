<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\alias\behaviors;

use yii\db\BaseActiveRecord;

/**
 * Trait AliasBehaviorTrait
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait AliasBehaviorTrait
{
    /**
     * @return array[]
     * @inheritdoc
     */
    public function getAliasDefinitions(): array
    {
        return [
            'timestampAlias' => [
                TimestampAliasBehavior::class,
                ['_time' => '_at'],
            ],
            'moneyAlias' => [
                MoneyAliasBehavior::class,
                ['' => '_cent'],
            ],
            'weightUnitAlias' => [
                [
                    'class' => UnitAliasBehavior::class,
                    'baseUnit' => 'g',
                    'displayUnit' => 'kg',
                ],
                ['_kg' => '_g'],
            ],
            'sizeUnitAlias' => [
                [
                    'class' => UnitAliasBehavior::class,
                    'baseUnit' => 'mm',
                    'displayUnit' => 'cm',
                ],
                ['_cm' => '_mm'],
            ],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function aliasBehaviors(): array
    {
        $behaviors = [];
        /** @var BaseActiveRecord $this */
        $attributes = $this->attributes();
        foreach ($this->getAliasDefinitions() as $name => [$behaviorConfig, $attributeAlias]) {
            $aliasProperties = [];
            foreach ($attributeAlias as $aliasSuffix => $attributeSuffix) {
                $suffixLength = strlen($attributeSuffix);
                foreach ($attributes as $attribute) {
                    if (substr($attribute, -$suffixLength) === $attributeSuffix) {
                        $alias = substr($attribute, 0, -$suffixLength) . $aliasSuffix;
                        $aliasProperties[$alias] = $attribute;
                    }
                }
            }
            if ($aliasProperties) {
                if (is_string($behaviorConfig)) {
                    $behaviorConfig = ['class' => $behaviorConfig];
                }
                $behaviors[$name] = array_merge($behaviorConfig, ['aliasProperties' => $aliasProperties]);
            }
        }
        return $behaviors;
    }
}
