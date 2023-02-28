<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\helpers;

use lujie\extend\helpers\ValueHelper;
use lujie\sales\channel\models\OttoAttribute;
use lujie\sales\channel\models\OttoCategoryGroup;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

/**
 * Class OttoHelper
 * @package lujie\sales\channel\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoHelper
{
    /**
     * @param string $categoryGroup
     * @param array $attributeValues
     * @return array
     * @inheritdoc
     */
    public static function formatProductAttributes(string $categoryGroup, array $attributeValues): array
    {
        $ottoCategoryGroup = OttoCategoryGroup::find()->categoryGroup($categoryGroup)->one();
        if ($ottoCategoryGroup === null) {
            throw new InvalidArgumentException('Invalid otto category group: ' . $categoryGroup);
        }
        /** @var OttoAttribute[] $ottoAttributes */
        $ottoAttributes = ArrayHelper::index($ottoCategoryGroup->groupAttributes, 'name');
        $attributeValues = array_filter($attributeValues, [ValueHelper::class, 'notEmpty']);
        $productAttributes = [];
        foreach ($attributeValues as $name => $values) {
            $ottoAttribute = $ottoAttributes[$name] ?? null;
            if ($ottoAttribute === null) {
                continue;
            }
            $values = (array)$values;
            if ($ottoAttribute->type === OttoAttribute::TYPE_FLOAT) {
                $values = array_map(static function($v) {
                    return (float)$v;
                }, $values);
            } else if ($ottoAttribute->type === OttoAttribute::TYPE_INTEGER) {
                $values = array_map(static function($v) {
                    return (int)$v;
                }, $values);
            } else if ($ottoAttribute->type === OttoAttribute::TYPE_STRING) {
                $values = array_map(static function($v) {
                    return (string)$v;
                }, $values);
            }
            $productAttributes[] = [
                'name' => $name,
                'values' => $values,
                'additional' => empty($ottoAttribute->allowed_values),
            ];
        }
        return $productAttributes;
    }
}