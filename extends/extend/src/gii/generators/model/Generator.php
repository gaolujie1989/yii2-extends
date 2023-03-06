<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\gii\generators\model;

use yii\db\Expression;
use yii\db\Schema;

/**
 * Class Generator
 * @package lujie\extend\gii\generators\model
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Generator extends \yii\gii\generators\model\Generator
{
    /**
     * @return string
     * @throws \ReflectionException
     * @inheritdoc
     */
    public function formView(): string
    {
        $class = new \ReflectionClass(\yii\gii\generators\model\Generator::class);

        return dirname($class->getFileName()) . '/form.php';
    }

    /**
     * @param \yii\db\TableSchema $table
     * @return array
     * @inheritdoc
     */
    public function generateRules($table): array
    {
        return array_merge(
            $this->generateDefaultRules($table),
            parent::generateRules($table)
        );
    }

    /**
     * @param \yii\db\TableSchema $table
     * @return array
     * @inheritdoc
     */
    protected function generateDefaultRules($table): array
    {
        $defaults = [];
        foreach ($table->columns as $column) {
            if ($column->autoIncrement) {
                continue;
            }
            if ($column->defaultValue !== null && !($column->defaultValue instanceof Expression)) {
                $defaults[$column->defaultValue][] = $column->name;
            } elseif ($column->allowNull) {
                switch ($column->type) {
                    case Schema::TYPE_JSON:
                        $defaults['EMPTY_ARRAY'][] = $column->name;
                        break;
                    case Schema::TYPE_TEXT:
                        $defaults[''][] = $column->name;
                    // no break
                    default:
                }
            }
        }
        $rules = [];
        foreach ($defaults as $defaultValue => $columns) {
            if ($defaultValue === 'EMPTY_ARRAY') {
                $defaultValue = '[]';
            } else {
                $defaultValue = is_string($defaultValue) ? "'$defaultValue'" : $defaultValue;
            }
            $rules[] = "[['" . implode("', '", $columns) . "'], 'default', 'value' => {$defaultValue}]";
        }
        return $rules;
    }

    /**
     * @param \yii\db\TableSchema $table
     * @return array
     * @inheritdoc
     */
    protected function generateProperties($table): array
    {
        $properties = parent::generateProperties($table);
        foreach ($table->columns as $column) {
            if ($column->type === Schema::TYPE_JSON) {
                $type = 'array';
            } else {
                continue;
            }
            if ($column->allowNull) {
                $type .= '|null';
            }
            $properties[$column->name] = [
                'type' => $type,
                'name' => $column->name,
                'comment' => $column->comment,
            ];
        }
        return $properties;
    }
}
