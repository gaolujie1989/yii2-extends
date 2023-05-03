<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use Yii;
use yii\authclient\CacheStateStorage;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Trait RestTrait
 *
 * @property array $resources
 * @property array $extraActions
 * @property array $extraMethods
 * @property string $suffix
 *
 * @property string|array $cacheStorage
 * @property array $httpClientOptions
 *
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait RestApiTrait
{
    /**
     * @var array default resource actions
     */
    public $actions = [
        'list' => ['GET', ''],
        'get' => ['GET', '{id}'],
        'create' => ['POST', ''],
        'update' => ['PUT', '{id}'],
        'delete' => ['DELETE', '{id}'],
    ];

    /**
     * @var array
     */
    public $pluralize = ['list'];

    /**
     * @var array
     */
    public $methods = [];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->initRest();
    }

    #region Rest method generate

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function initRest(): void
    {
        Yii::info('Init rest config and api methods', __METHOD__);
        $this->setStateStorage($this->cacheStorage ?? CacheStateStorage::class);
        $this->setHttpClient(array_merge([
            'requestConfig' => [
                'format' => 'json'
            ],
            'responseConfig' => [
                'format' => 'json'
            ],
        ], $this->httpClientOptions ?? []));
        $this->methods = array_merge($this->createRestApiMethods(), $this->extraMethods ?? []);
        if (isset($this->suffix)) {
            $this->methods = array_map(function (array $method) {
                $method[1] .= $this->suffix;
                return $method;
            }, $this->methods);
        }
        if ($this->getId() === $this->getName()) {
            $identityKey = $this->username ?? $this->apiKey ?? $this->clientId ?? '';
            if (empty($identityKey)) {
                throw new InvalidConfigException('The identity key is required.');
            }
            $this->setId($this->getId() . '-' . $identityKey);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function createRestApiMethods(): array
    {
        $apiMethods = [];
        $resources = $this->resources ?? [];
        $extraActions = $this->extraActions ?? [];
        $pluralize = $this->pluralize ?? ['list'];
        foreach ($resources as $resource => $resourcePath) {
            $resourceActions = array_filter(array_merge($this->actions, $extraActions[$resource] ?? []));
            foreach ($resourceActions as $action => [$httpMethod, $actionPath]) {
                $url = $resourcePath . ($actionPath ? '/' . trim($actionPath) : '');
                $method = $action . (in_array($action, $pluralize, true) ? Inflector::pluralize($resource) : $resource);
                $apiMethods[$method] = [$httpMethod, $url];
            }
        }
        return $apiMethods;
    }

    #endregion

    #region Method Alias Call

    /**
     * @param string $path
     * @return array
     * @inheritdoc
     */
    protected function getPathParams(string $path): array
    {
        if (preg_match_all('/{([^{}\s]+)}/', $path, $matches)) {
            return $matches[1];
        }
        return [];
    }

    /**
     * @param string $path
     * @param array $params
     * @return string
     * @throws \Exception
     * @inheritdoc
     */
    public function getRealPath(string $path, array &$params, bool $removePathParam = false): string
    {
        $pathParams = $this->getPathParams($path);
        if ($pathParams && $params) {
            $pathParamValues = [];
            foreach ($pathParams as $pathParam) {
                $pathParamValues['{' . $pathParam . '}'] = ArrayHelper::getValue($params, $pathParam);
                if ($removePathParam) {
                    ArrayHelper::remove($params, $pathParam);
                }
            }
            return strtr($path, $pathParamValues);
        }
        return $path;
    }

    /**
     * @param string $name
     * @param array $data
     * @return array
     * @inheritdoc
     * @throws \Exception
     */
    public function restApi(string $name, array $data): ?array
    {
        if (empty($this->methods[$name])) {
            throw new InvalidArgumentException("API method {$name} not found.");
        }

        [$method, $url] = $this->methods[$name];
        $method = strtoupper($method);
        $url = $this->getRealPath($url, $data, !empty($this->methods[$name][2]));

        if ($method === 'GET' && $data) {
            $url = array_merge([$url], $data);
            $data = [];
        }
        return $this->api($url, $method, $data);
    }

    /**
     * @param string $name
     * @param array|mixed $params
     * @return array|\Iterator|void|null
     * @throws \Exception
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (isset($this->methods[$name])) {
            return $this->restApi($name, $params[0] ?? []);
        }

        if (strpos($name, 'batch') === 0) {
            return $this->batch(substr($name, 5), $params[0] ?? [], $params[1] ?? 100);
        }

        if (strpos($name, 'each') === 0) {
            return $this->each(substr($name, 4), $params[0] ?? [], $params[1] ?? 100);
        }

        parent::__call($name, $params);
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function methodDoc(): string
    {
        $apiMethodDocs = [];
        foreach ($this->methods as $method => [$httpMethod, $url]) {
            if (strpos($method, 'list') === 0) {
                $name = substr($method, 4);
                $apiMethodDocs[] = " * ";
                $apiMethodDocs[] = " * @method array {$method}(\$data = [])";
                $apiMethodDocs[] = " * @method \Generator each{$name}(\$condition = [], \$batchSize = 100)";
                $apiMethodDocs[] = " * @method \Generator batch{$name}(\$condition = [], \$batchSize = 100)";
            } else {
                $apiMethodDocs[] = " * @method array {$method}(\$data)";
            }
        }
        return implode("\n", $apiMethodDocs);
    }

    #endregion
}
