<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\extend\validators;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Class DateValidator
 * @package lujie\extend\validator
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DateValidator extends \yii\validators\DateValidator
{
    public const TIMESTAMP_ATTRIBUTE_SELF = 'SELF';
    public const TIMESTAMP_ATTRIBUTE_FORMAT_BY_TYPE = 'BY_TYPE';

    /**
     * @var string
     */
    public $parseDateValueFunc = 'strtotime';

    /**
     * @var string
     */
    public $timestampAttribute = self::TIMESTAMP_ATTRIBUTE_SELF;

    /**
     * @var string
     */
    public $timestampAttributeTimeZone;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->timestampAttributeTimeZone)) {
            $this->timestampAttributeTimeZone = Yii::$app->timeZone;
        }
        if ($this->timestampAttributeFormat === self::TIMESTAMP_ATTRIBUTE_FORMAT_BY_TYPE) {
            if ($this->type === self::TYPE_DATE) {
                $this->timestampAttributeFormat = 'php:Y-m-d';
            } elseif ($this->type === self::TYPE_DATETIME) {
                $this->timestampAttributeFormat = 'php:Y-m-d H:i:s';
            } elseif ($this->type === self::TYPE_TIME) {
                $this->timestampAttributeFormat = 'php:H:i:s';
            } else {
                throw new InvalidConfigException('Unknown validation type set for DateValidator::$type: ' . $this->type);
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function parseDateValue($value)
    {
        if (is_numeric($value)) {
            return (int)substr($value, 0, 10);
        }
        if ($this->parseDateValueFunc) {
            return call_user_func($this->parseDateValueFunc, $value);
        }
        return parent::parseDateValue($value);
    }

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute): void
    {
        if ($this->timestampAttribute === self::TIMESTAMP_ATTRIBUTE_SELF) {
            $this->timestampAttribute = $attribute;
            parent::validateAttribute($model, $attribute);
            $this->timestampAttribute = self::TIMESTAMP_ATTRIBUTE_SELF;
        } else {
            parent::validateAttribute($model, $attribute);
        }
    }
}
