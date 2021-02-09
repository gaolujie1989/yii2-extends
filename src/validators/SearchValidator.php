<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\validators;

/**
 * Class SearchValidator
 * @package lujie\extend\validators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SearchValidator extends \yii\validators\Validator
{
    /**
     * @var string
     */
    public $split = '/[,;\s]/';

    public $likePrefix;

    /**
     * {@inheritdoc}
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->{$attribute};
        $splitValues = preg_split($this->split, $value, -1, PREG_SPLIT_NO_EMPTY);
        $model->{$attribute} = $splitValues;
    }
}