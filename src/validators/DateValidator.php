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
    public const TIMESTAMP_ATTRIBUTE_FORMAT_AUTO = 'AUTO';
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
    public $timestampAttributeFormat = self::TIMESTAMP_ATTRIBUTE_FORMAT_AUTO;

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
            $this->setTimestampAttributeFormatByType();
        }
    }

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    private function setTimestampAttributeFormatByType(): void
    {
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

    /**
     * @param string $attribute
     * @inheritdoc
     */
    private function setAutoTimestampAttributeFormat(string $attribute): void
    {
        if (substr($attribute, -3) === '_at') {
            $this->timestampAttributeFormat = null;
        } else if (substr($attribute, -5) === '_date') {
            $this->timestampAttributeFormat = 'php:Y-m-d';
        } else if (substr($attribute, -9) === '_datetime' || substr($attribute, -5) === '_time') {
            $this->timestampAttributeFormat = 'php:Y-m-d H:i:s';
        } else {
            $this->timestampAttributeFormat = null;
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
        $isAutoFormat = ($this->timestampAttributeFormat === self::TIMESTAMP_ATTRIBUTE_FORMAT_AUTO);
        $isSelfAttribute = ($this->timestampAttribute === self::TIMESTAMP_ATTRIBUTE_SELF);
        if ($isAutoFormat) {
            $this->setAutoTimestampAttributeFormat($attribute);
        }
        if ($isSelfAttribute) {
            $this->timestampAttribute = $attribute;
        }

        parent::validateAttribute($model, $attribute);

        if ($isAutoFormat) {
            $this->timestampAttributeFormat = self::TIMESTAMP_ATTRIBUTE_FORMAT_AUTO;
        }
        if ($isSelfAttribute) {
            $this->timestampAttribute = self::TIMESTAMP_ATTRIBUTE_SELF;
        }
    }
}
