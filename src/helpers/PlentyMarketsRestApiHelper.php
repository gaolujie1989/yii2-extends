<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\plentyMarkets\helpers;

use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;

/**
 * Class PlentyMarketsRestApiJsonHelper
 * @package lujie\plentyMarkets\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PlentyMarketsRestApiHelper
{
    public static function formatSchemaJson(string $file): void
    {
        $apiSchema = Json::decode(file_get_contents($file));
        $tagApiSchemas = [];
        foreach ($apiSchema['tags'] as $tag) {
            $tagApiSchemas[$tag['name']] = $apiSchema;
            $tagApiSchemas[$tag['name']]['paths'] = [];
        }
        foreach ($apiSchema['paths'] as $path => $methods) {
            foreach ($methods as $httpMethod => $method) {
                $tag = $method['tags'][0];
                $tagApiSchemas[$tag]['paths'][$path][$httpMethod] = $method;
            }
        }
        foreach ($tagApiSchemas as $tagName => $tagApiSchema) {
            $tagApiFile = dirname($file) . '/' . $tagName . '.json';
            file_put_contents($tagApiFile, Json::encode($tagApiSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }

    /**
     * @param string $path
     * @param string $httpMethod
     * @return string
     * @inheritdoc
     */
    public static function getApiMethodName(string $path, string $httpMethod, array $params): string
    {
        $methodAction = strtolower($httpMethod);
        if ($methodAction === 'post') {
            $methodAction = 'create';
        }
        if ($methodAction === 'put') {
            $methodAction = 'update';
        }
        $path = strtr($path, ['/rest' => '']);
        $name = $methodAction . ' ' . strtr(preg_replace('/\{[\w_]+\}/', '', $path), ['/' => ' ']);
        $name = lcfirst(Inflector::camelize($name));
        if ($methodAction !== 'get' || str_ends_with($path, '}')) {
            $name = Inflector::singularize($name);
        }
        $queryParamNames = ArrayHelper::getColumn($params['path'] ?? [], 'name');
        if ($queryParamNames) {
            $queryParamNames = array_map(static fn($v) => ucfirst(rtrim($v, '?')), $queryParamNames);
            $suffix = 'By' . implode('', $queryParamNames);
            $name .= $suffix;
        }
        return $name;
    }
}
