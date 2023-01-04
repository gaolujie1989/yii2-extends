<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\searches;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\common\option\models\Option;
use lujie\common\option\models\OptionTrait;
use yii\helpers\ArrayHelper;

/**
 * Trait OptionFormTrait
 * @package lujie\common\option\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait OptionFormTrait
{
    use OptionTrait;

    /**
     * @return array[]
     * @inheritdoc
     */
    public function optionBehaviors(): array
    {
        return [
            'modelOptionSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['modelOptions'],
                'indexKeys' => ['modelOptions' => 'option_id']
            ],
            'modelOptionDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['modelOptions'],
            ]
        ];
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
}