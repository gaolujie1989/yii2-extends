<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\validators;

use yii\db\BaseActiveRecord;
use yii\validators\Validator;

/**
 * Class SkipValidator
 * @package lujie\extend\validators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SkipValidator extends Validator
{
    /**
     * @var bool
     */
    public $skipOnEmpty = false;

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute): void
    {
        if ($model instanceof BaseActiveRecord && $this->isEmpty($model->{$attribute})) {
            $model->{$attribute} = $model->getOldAttribute($attribute);
        }
    }
}