<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\otto\helpers;

use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Class OttoRestApiHelper
 * @package lujie\otto\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoRestApiHelper
{
    /**
     * @param string $apiMethod
     * @param string $httpMethod
     * @return string
     * @inheritdoc
     */
    public static function getApiMethodName(string $apiMethod, string $httpMethod): string
    {
        if (!str_contains($apiMethod, '__') || !str_contains($apiMethod, '-')) {
            return $apiMethod;
        }
        [$methodPrefix, $methodName] = explode('__', $apiMethod);
        $methodPrefixParts = explode('-', $methodPrefix);
        $methodVersion = array_pop($methodPrefixParts);
        $methodGroups = $methodPrefixParts;
        foreach ($methodGroups as $key => $methodGroup) {
            if (str_contains($methodName, $methodGroup)) {
                $methodGroup = '';
            }
            $methodGroup = Inflector::singularize($methodGroup);
            if (str_contains($methodName, $methodGroup)) {
                $methodGroup = '';
            }
            $methodGroups[$key] = $methodGroup;
        }
        $methodGroup = implode('', $methodGroups);

        $methodName = strtr($methodName, ['UsingGET' => '', 'UsingPOST' => '', $methodVersion => '']);
        [$methodName] = explode('_', $methodName);
        $methodNameParts = explode('-', Inflector::camel2id($methodName));
        if ($httpMethod === 'GET' && !in_array(reset($methodNameParts), ['get', 'list'], true)) {
            $action = 'get';
        } else  {
            $action = array_shift($methodNameParts);
        }
        if (in_array(reset($methodNameParts), ['or', 'and'], true)) {
            $action .= ucfirst(array_shift($methodNameParts)) . ucfirst(array_shift($methodNameParts));
        }

        return lcfirst(Inflector::camelize(implode('-', array_merge([$action, $methodVersion, $methodGroup], $methodNameParts))));
    }
}
