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
     * @param $modelClass
     * @return mixed|null
     * @inheritdoc
     */
    public static function getFormClass($modelClass)
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
     * @param $modelClass
     * @return mixed|null
     * @inheritdoc
     */
    public static function getSearchClass($modelClass)
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
     * @param $modelOrClass
     * @return string
     * @inheritdoc
     */
    public static function getBaseRecordClass($modelOrClass)
    {
        if (!is_subclass_of($modelOrClass, BaseActiveRecord::class)) {
            throw new InvalidArgumentException('Model or class not subclass of BaseActiveRecord');
        }
        $parentClass = get_parent_class($modelOrClass);
        if (strpos($parentClass, 'ActiveRecord') !== false) {
            return is_object($modelOrClass) ? get_class($modelOrClass) : $modelOrClass;
        } else {
            return static::getBaseRecordClass($parentClass);
        }
    }
}
