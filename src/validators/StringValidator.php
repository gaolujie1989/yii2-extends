<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\validators;

use function PHPUnit\Framework\stringContains;

/**
 * Class StringValidator
 * @package lujie\extend\validators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StringValidator extends \yii\validators\StringValidator
{
    public $trim = true;

    public $convert = true;

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute): void
    {
        $value = $model->{$attribute};
        if ($this->trim && is_string($value)) {
            $value = trim($value);
        }
        if ($this->convert && !is_string($value)) {
            $value = (string)$value;
        }
        $model->{$attribute} = $value;
        parent::validateAttribute($model, $attribute);
    }

    /**
     * @param mixed $value
     * @return array|null
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    protected function validateValue($value): ?array
    {
        if ($this->trim) {
            $value = trim($value);
        }
        return parent::validateValue($value);
    }
}
