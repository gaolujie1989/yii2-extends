<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use yii\base\InvalidArgumentException;
use yii\db\BaseActiveRecord;

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
    public static function getFormClass(string $modelClass): ?string
    {
        $formClasses = [
            strtr($modelClass, ['models' => 'forms']) . 'Form',
            $modelClass . 'Form',
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
    public static function getSearchClass(string $modelClass): ?string
    {
        $searchClasses = [
            strtr($modelClass, ['models' => 'searches']) . 'Search',
            $modelClass . 'Search',
        ];
        foreach ($searchClasses as $searchClass) {
            if (class_exists($searchClass)) {
                return $searchClass;
            }
        }
        return null;
    }

    /**
     * @param string $modelClass
     * @return string|null
     * @inheritdoc
     */
    public static function getBatchFormClass(string $modelClass): ?string
    {
        $batchFormClasses = [
            strtr($modelClass, ['models' => 'forms']) . 'BatchForm',
            $modelClass . 'BatchForm',
        ];
        foreach ($batchFormClasses as $formClass) {
            if (class_exists($formClass)) {
                return $formClass;
            }
        }
        return null;
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
        if (strpos($parentClass, 'ActiveRecord') !== false) {
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
