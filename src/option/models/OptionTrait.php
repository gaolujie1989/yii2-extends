<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\models;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Trait OptionTrait
 *
 * @property string $optionKey = 'tags'
 * @property string|Option $optionClass
 * @property string|ModelOption $modelOptionClass
 *
 * @property Option[] $options
 * @property ModelOption[] $modelOptions
 *
 * @package lujie\common\option\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait OptionTrait
{
    /**
     * @return array[]
     * @inheritdoc
     */
    protected function optionRules(): array
    {
        $optionKey = $this->optionKey ?? 'tags';
        return [
            [[$optionKey], 'safe'],
        ];
    }

    /**
     * @return string[]
     * @inheritdoc
     */
    public function optionExtraFields(): array
    {
        $optionKey = $this->optionKey ?? 'tags';
        return [
            'options' => 'options',
            $optionKey => 'optionValues'
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getModelOptions(): ActiveQuery
    {
        $primaryKey = static::primaryKey();
        $modelOptionClass = $this->modelOptionClass ?? ModelOption::class;
        return $this->hasMany($modelOptionClass, ['model_id' => $primaryKey[0]]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getOptions(): ActiveQuery
    {
        $optionClass = $this->optionClass ?? Option::class;
        return $this->hasMany($optionClass::class, ['option_id' => 'option_id'])->via('modelOptions');
    }
}