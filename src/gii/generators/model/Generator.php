<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\gii\generators\model;

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
            if ($column->defaultValue !== null) {
                $defaults[$column->defaultValue][] = $column->name;
            }
        }
        $rules = [];
        foreach ($defaults as $defaultValue => $columns) {
            $defaultValue = is_string($defaultValue) ? "'$defaultValue'" : $defaultValue;
            $rules[] = "[['" . implode("', '", $columns) . "'], 'default', 'value' => {$defaultValue}]";
        }
        return $rules;
    }
}
