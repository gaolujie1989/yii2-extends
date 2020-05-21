<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use Iterator;
use Throwable;
use yii\authclient\CacheStateStorage;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\NotFoundHttpException;

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
trait RestClientTrait
{
    /**
     * @var array default resource actions
     */
    private $actions = [
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
    public $apiMethods = [];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->initRest();
    }

    /**
     * @inheritdoc
     */
    protected function initRest(): void
    {
        $this->setStateStorage($this->cacheStorage ?? CacheStateStorage::class);
        $this->setHttpClient($this->httpClientOptions ?? [
                'requestConfig' => [
                    'format' => 'json'
                ],
                'responseConfig' => [
                    'format' => 'json'
                ],
            ]);
        $this->apiMethods = array_merge($this->createApiMethods(), $this->extraMethods ?? []);
    }

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
     * @inheritdoc
     */
    protected function getRealPath(string $path, array $params): string
    {
        $pathParams = $this->getPathParams($path);
        if ($pathParams && $params) {
            $pathParamValues = [];
            foreach ($pathParams as $pathParam) {
                $pathParamValues['{' . $pathParam . '}'] = ArrayHelper::getValue($params, $pathParam);
            }
            return strtr($path, $pathParamValues);
        }
        return $path;
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function createApiMethods(): array
    {
        $apiMethods = [];
        $resources = $this->resources ?? [];
        $extraActions = $this->extraActions ?? [];
        $pluralize = $this->pluralize ?? ['list'];
        foreach ($resources as $resource => $resourcePath) {
            $resourceActions = array_filter(array_merge($this->actions, $extraActions[$resource] ?? []));
            foreach ($resourceActions as $action => [$httpMethod, $actionPath]) {
                $url = $resourcePath . ($actionPath ? '/' . trim($actionPath) : '') . ($this->suffix ?? '');
                $method = $action . (in_array($action, $pluralize, true) ? Inflector::pluralize($resource) : $resource);
                $apiMethods[$method] = [$httpMethod, $url];
            }
        }
        return $apiMethods;
    }

    /**
     * @param string $name
     * @param array $data
     * @return array
     * @inheritdoc
     */
    protected function callApiMethod(string $name, array $data): array
    {
        if (empty($this->apiMethods)) {
            $this->apiMethods = array_merge($this->createApiMethods(), $this->extraMethods ?? []);
        }
        if (empty($this->apiMethods[$name])) {
            throw new InvalidArgumentException("API method {$name} not found.");
        }

        [$method, $url] = $this->apiMethods[$name];
        $method = strtoupper($method);
        $url = $this->getRealPath($url, $data);

        if ($method === 'GET' && $data) {
            $url = array_merge([$url], $data);
            $data = [];
        }
        return $this->api($url, $method, $data);
    }

    /**
     * @param string $name
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function restApi(string $name, array $data): array
    {
        return $this->callApiMethod($name, $data);
    }

    /**
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    abstract public function batch(string $resource, array $condition = [], int $batchSize = 100): Iterator;

    /**
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function each(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        $iterator = $this->batch($resource, $condition, $batchSize);
        foreach ($iterator as $items) {
            yield from $items;
        }
    }

    /**
     * @param string $name
     * @param array $params
     * @return array|mixed
     * @throws NotFoundHttpException
     * @throws Throwable
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (isset($this->apiMethods[$name])) {
            return $this->restApi($name, $params[0] ?? []);
        }

        if (strpos($name, 'batch') === 0) {
            return $this->batch(substr($name, 4), $params[0] ?? [], $params[1] ?? 100);
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
    public function generateMethodDoc(): string
    {
        if (empty($this->apiMethods)) {
            $this->apiMethods = array_merge($this->createApiMethods(), $this->apiMethods);
        }
        $apiMethodDocs = [];
        foreach ($this->apiMethods as $method => [$httpMethod, $url]) {
            if (strpos($method, 'list') === 0) {
                $name = Inflector::singularize(substr($method, 4));
                $apiMethodDocs[] = " * @method array {$method}(\$data = [])";
                $apiMethodDocs[] = " * @method \Generator each{$name}(\$condition = [], \$batchSize = 100)";
                $apiMethodDocs[] = " * @method \Generator batch{$name}(\$condition = [], \$batchSize = 100)";
            } else {
                $apiMethodDocs[] = " * @method array {$method}(\$data)";
            }
        }
        return implode("\n", $apiMethodDocs);
    }
}