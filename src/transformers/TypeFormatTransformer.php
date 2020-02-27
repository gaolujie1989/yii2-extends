<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\transformers;

use yii\base\BaseObject;

/**
 * Class FormatTransformer
 * @package lujie\data\exchange\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TypeFormatTransformer extends BaseObject implements TransformerInterface
{
    public const TYPE_STRING = 'STRING';
    public const TYPE_STRING_TRIM = 'STRING_TRIM';
    public const TYPE_INTEGER = 'INTEGER';
    public const TYPE_INTEGER_ROUND = 'INTEGER_ROUND';
    public const TYPE_FLOAT = 'FLOAT';

    public $defaultType = self::TYPE_STRING;

    public $keyTypes = [];

    /**
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        return array_map(function($values) {
            foreach ($values as $key => $value) {
                $dateType = $this->keyTypes[$key] ?? $this->defaultType;
                $values[$key] = $this->convertType($dateType, $value);
            }
            return $values;
        }, $data);
    }

    /**
     * @param string $type
     * @param mixed $value
     * @return false|float|int|string
     * @inheritdoc
     */
    public function convertType(string $type, $value)
    {
        if ($value === null || $value === '') {
            return $value;
        }
        switch ($type) {
            case self::TYPE_STRING:
                $value = (string)$value;
                break;
            case self::TYPE_STRING_TRIM:
                $value = trim($value);
                break;
            case self::TYPE_INTEGER:
                $value = (int)$value;
                break;
            case self::TYPE_INTEGER_ROUND:
                $value = round($value);
                break;
            case self::TYPE_FLOAT:
                $value = (float)$value;
                break;
            default:
                break;
        }
        return $value;
    }
}
