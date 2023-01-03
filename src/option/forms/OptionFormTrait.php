<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\searches;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\common\option\models\ModelOption;
use lujie\common\option\models\Option;
use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Trait OptionFormTrait
 *
 * @property string $optionKey = 'options'
 * @property string|Option $optionClass
 * @property string|ModelOption $modelOptionClass
 *
 * @property Option[] $options
 * @property ModelOption[] $modelOptions
 *
 * @package lujie\common\option\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait OptionFormTrait
{
    /**
     * @param array $optionValues
     * @inheritdoc
     */
    public function setOptionValues(array $optionValues): void
    {
        $optionClass = $this->optionClass ?? Option::class;
        $optionIds = $optionClass::find()->value($optionValues)->getIds();
        $this->modelOptions = array_map(static function($optionId) {
            return ['option_id' => $optionId];
        }, $optionIds);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getOptionValues(): array
    {
        return ArrayHelper::getColumn($this->options, 'value');
    }

    /**
     * @return array[]
     * @inheritdoc
     */
    public function optionBehaviors(): array
    {
        return [
            'tagSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['modelOptions'],
                'indexKeys' => ['modelOptions' => 'option_id']
            ],
            'tagDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['modelOptions'],
            ]
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getModelOptions(): ActiveQuery
    {
        /** @var BaseActiveRecord $this */
        $primaryKey = $this::primaryKey();
        $modelOptionClass = $this->modelOptionClass ?? ModelOption::class;
        return $this->hasMany($modelOptionClass, ['model_id' => $primaryKey[0]]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getOptions(): ActiveQuery
    {
        /** @var BaseActiveRecord $this */
        $optionClass = $this->optionClass ?? Option::class;
        return $this->hasMany($optionClass::class, ['option_id' => 'option_id'])->via('modelOptions');
    }
}