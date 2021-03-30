<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\validators;

/**
 * Class NumberValidator
 * @package lujie\extend\validators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class NumberValidator extends \yii\validators\NumberValidator
{
    public $convert = true;

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute): void
    {
        parent::validateAttribute($model, $attribute);
        if ($this->convert && !$model->hasErrors($attribute)) {
            $value = $model->{$attribute};
            $model->{$attribute} = $this->integerOnly ? (int)$value : (float)$value;
        }
    }
}
