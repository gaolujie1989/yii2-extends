<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use yii\base\InvalidArgumentException;
use yii\db\BaseActiveRecord;
use yii\helpers\StringHelper;

/**
 * Class ClassHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ClassHelper
{
    /**
     * @param string $modelClass
     * @return string|null
     * @inheritdoc
     */
    protected static function getModelClass(string $modelClass, $replaces = ['models' => 'forms'], $suffix = 'Form'): ?string
    {
        $formClasses = [
            strtr($modelClass, $replaces) . $suffix,
            $modelClass . $suffix,
            strtr($modelClass, ['\\models' => '']) . $suffix
        ];
        foreach ($formClasses as $formClass) {
            if (class_exists($formClass)) {
                return $formClass;
            }
        }
        return null;
    }

    /**
     * @param string $modelClass
     * @return string|null
     * @inheritdoc
     */
    public static function getFormClass(string $modelClass): ?string
    {
        return static::getModelClass($modelClass, ['models' => 'forms'], 'Form');
    }

    /**
     * @param string $modelClass
     * @return string|null
     * @inheritdoc
     */
    public static function getSearchClass(string $modelClass): ?string
    {
        return static::getModelClass($modelClass, ['models' => 'searches'], 'Search');
    }

    /**
     * @param string $modelClass
     * @return string|null
     * @inheritdoc
     */
    public static function getBatchFormClass(string $modelClass): ?string
    {
        return static::getModelClass($modelClass, ['models' => 'forms'], 'BatchForm');
    }

    /**
     * @param string $modelClass
     * @return string|null
     * @inheritdoc
     */
    public static function getImportFormClass(string $modelClass): ?string
    {
        return static::getModelClass($modelClass, ['models' => 'forms'], 'FileImportForm');
    }

    /**
     * @param string $modelClass
     * @return string|null
     * @inheritdoc
     */
    public static function getImporterClass(string $modelClass): ?string
    {
        return static::getModelClass($modelClass, ['models' => 'importers'], 'FileImporter');
    }

    /**
     * @param string $modelClass
     * @return string|null
     * @inheritdoc
     */
    public static function getExporterClass(string $modelClass): ?string
    {
        return static::getModelClass($modelClass, ['models' => 'exporters'], 'FileExporter');
    }

    /**
     * @param BaseActiveRecord|string $modelOrClass
     * @return string|null
     * @inheritdoc
     */
    public static function getBaseRecordClass($modelOrClass): ?string
    {
        if (!is_subclass_of($modelOrClass, BaseActiveRecord::class)) {
            throw new InvalidArgumentException('Model or class not subclass of BaseActiveRecord');
        }
        $parentClass = get_parent_class($modelOrClass);
        if ($parentClass === BaseActiveRecord::class || StringHelper::endsWith($parentClass, '\\ActiveRecord')) {
            return is_object($modelOrClass) ? get_class($modelOrClass) : $modelOrClass;
        }
        return static::getBaseRecordClass($parentClass);
    }

    /**
     * @param object|string $modelOrClass
     * @return string
     * @inheritdoc
     */
    public static function getClassShortName($modelOrClass): string
    {
        $class = is_string($modelOrClass) ? $modelOrClass : get_class($modelOrClass);
        $classParts = explode('\\', $class);
        return end($classParts);
    }
}
