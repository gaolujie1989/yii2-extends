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
        $optionTypes = array_combine($optionTypes, array_map([Inflector::class, 'camel2words'], $optionTypes));
        $optionData[Option::TYPE_OPTION_TYPE] = array_merge($optionTypes, $optionData[Option::TYPE_OPTION_TYPE]);

        $updateData = [];
        foreach ($optionData as $type => $typeOptions) {
            $position = 0;
            foreach ($typeOptions as $key => $optionItem) {
                $position++;
                if (!is_array($optionItem)) {
                    $optionItem = ['type' => $type, 'value' => $key, 'name' => $optionItem];
                }
                if (!isset($optionItem['value'])) {
                    $optionItem['value'] = $key;
                }
                if (empty($optionItem['type'])) {
                    $optionItem['type'] = $type;
                }
                if (empty($optionItem['position'])) {
                    $optionItem['position'] = $position;
                }
                $name = $optionItem['name'];
                if (strpos($name, '_') !== false || (strlen($name) > 4 && !preg_match('/[a-z]/', $name))) {
                    $nameParts = explode('_', $name);
                    $nameParts = array_map(static function($str) {
                        if (strlen($str) <= 4 && !in_array($str, ['TAG', 'TYPE'], true)) {
                            return $str;
                        }
                        return ucfirst(strtolower($str));
                    }, $nameParts);
                    $optionItem['name'] = implode(' ', $nameParts);
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
}