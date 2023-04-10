<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\helpers;

use lujie\common\option\models\Option;
use lujie\data\exchange\pipelines\DbPipeline;
use lujie\extend\helpers\CsvHelper;
use lujie\extend\helpers\ExcelHelper;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\helpers\StringHelper;

/**
 * Class OptionHelper
 * @package lujie\common\option\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionHelper
{
    public static $notUpperWords = ['IN', 'OUT', 'TO', 'TAG', 'NEW', 'NO', 'NOT', 'ON', 'OFF'];

    /**
     * @param string|array $fileOrData
     * @param bool $delete
     * @return array
     * @throws \ImagickException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \yii\db\IntegrityException
     * @inheritdoc
     */
    public static function updateOptions($fileOrData, bool $delete = false): array
    {
        if (is_array($fileOrData)) {
            $optionData = $fileOrData;
        } else {
            $file = $fileOrData;
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            switch ($ext) {
                case 'php':
                    $optionData = require $file;
                    break;
                case 'json':
                    $optionData = Json::decode(file_get_contents($file));
                    break;
                case 'txt':
                case 'csv':
                    $optionData = CsvHelper::readCsv($file);
                    $optionData = ArrayHelper::index($optionData, 'value', ['type']);
                    break;
                case 'xls':
                case 'xlsx':
                    $optionData = ExcelHelper::readExcel($file);
                    $optionData = ArrayHelper::index($optionData, 'value', ['type']);
                    break;
                default:
                    throw new InvalidArgumentException('Invalid file');
            }
        }

        //fill option types
        $optionTypes = array_keys($optionData);
        $optionTypeNames = array_map([Inflector::class, 'camel2words'], $optionTypes);
        $optionTypes = array_combine($optionTypeNames, $optionTypes);
        $optionData[Option::TYPE_OPTION_TYPE] = array_merge($optionTypes, $optionData[Option::TYPE_OPTION_TYPE]);

        $updateData = [];
        foreach ($optionData as $type => $typeOptions) {
            $position = 0;
            foreach ($typeOptions as $key => $optionItem) {
                $position++;
                if (!is_array($optionItem)) {
                    $optionItem = ['type' => $type, 'value' => $optionItem, 'name' => static::formatName($key)];
                }
                if (!isset($optionItem['value'])) {
                    $optionItem['value'] = $key;
                }
                if (!isset($optionItem['name'])) {
                    $optionItem['name'] = static::formatName($key);
                }
                if (empty($optionItem['type'])) {
                    $optionItem['type'] = $type;
                }
                if (empty($optionItem['position'])) {
                    $optionItem['position'] = $position;
                }
                if (!isset($optionItem['value_type'])) {
                    $value = $optionItem['value'];
                    if (is_int($value)) {
                        $optionItem['value_type'] = Option::VALUE_TYPE_INT;
                    } else if (is_float($value)) {
                        $optionItem['value_type'] = Option::VALUE_TYPE_FLOAT;
                    } else {
                        $optionItem['value_type'] = Option::VALUE_TYPE_STRING;
                    }
                }
                $typeOptions[$key] = $optionItem;
                $updateData[] = $optionItem;
            }
            $optionData[$type] = ArrayHelper::index($typeOptions, 'value');
        }

        $dbPipeline = new DbPipeline([
            'modelClass' => Option::class,
            'indexKeys' => ['type', 'value']
        ]);
        $dbPipeline->process($updateData);
        $affectedRowCounts = $dbPipeline->getAffectedRowCounts();

        if ($delete) {
            $deletedCount = 0;
            $optionTypeValues = ArrayHelper::map($updateData, 'value', 'value', 'type');
            foreach ($optionTypeValues as $type => $typeValues) {
                $existValues = Option::find()->type($type)->getValues();
                $toDeleteValues = array_diff($existValues, $typeValues);
                if ($toDeleteValues) {
                    $deletedCount += Option::deleteAll(['type' => $type, 'value' => $toDeleteValues]);
                }
            }
            $affectedRowCounts['deleted'] = $deletedCount;
        }

        return $affectedRowCounts;
    }

    /**
     * @param string $name
     * @return string
     * @inheritdoc
     */
    protected static function formatName(string $name): string
    {
        if (strpos($name, '_') !== false || strtoupper($name) === $name) {
            $nameParts = explode('_', $name);
            $nameParts = array_map(static function ($str) {
                if (strlen($str) <= 3 && !in_array($str, static::$notUpperWords, true)) {
                    return $str;
                }
                return ucfirst(strtolower($str));
            }, $nameParts);
            return implode(' ', $nameParts);
        }
        $name = Inflector::camel2words($name);
        $name = preg_replace('/(\w) (\d+)/', '$1$2', $name);
        return $name;
    }
}