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
    /**
     * @var string
     */
    public $parseDateValueFunc = 'strtotime';

    public $timestampAttributeTimeZone = null;

    public $timestampAttribute = true;

    public $timestampAttributeFormat = true;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (!$this->timestampAttributeTimeZone) {
            $this->timestampAttributeTimeZone = Yii::$app->timeZone;
        }
        if ($this->timestampAttributeFormat === true) {
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
        if (is_numeric($value) && strlen($value) > 11) {
            return (int)substr($value, 0, 11);
        }
        if ($this->parseDateValueFunc) {
            return call_user_func($this->parseDateValueFunc, $value);
        }
        return parent::parseDateValue($value);
    }

    /**
     * @param $model
     * @param $attribute
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute): void
    {
        if ($this->timestampAttribute === true) {
            $this->timestampAttribute = $attribute;
            parent::validateAttribute($model, $attribute);
            $this->timestampAttribute = true;
        } else {
            parent::validateAttribute($model, $attribute);
        }
    }
}
