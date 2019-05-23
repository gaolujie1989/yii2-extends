<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use yii\authclient\OAuth2;
use yii\helpers\Inflector;
use yii\httpclient\Client;
use yii\web\NotFoundHttpException;

/**
 * Class RestOAuth2Client
 * @package lujie\anyconnect\clients
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class RestOAuth2Client extends OAuth2
{
    /**
     * @var array
     */
    public $resources = [];

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
    public $extraActions = [];

    /**
     * @var array
     */
    public $pluralize = ['list'];

    /**
     * @var string
     */
    public $suffix = '';

    /**
     * @var array
     */
    public $apiMethods = [];

    /**
     * @var string
     */
    public $cacheStorage = CacheStateStorage::class;

    /**
     * @var array
     */
    public $httpClientOptions = [
        'class' => Client::class,
        'requestConfig' => [
            'format' => 'json'
        ],
        'responseConfig' => [
            'format' => 'json'
        ],
    ];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->cacheStorage) {
            $this->setStateStorage($this->cacheStorage);
        }
        if ($this->httpClientOptions) {
            $this->setHttpClient($this->httpClientOptions);
        }
        $this->apiMethods = array_merge($this->createApiMethods(), $this->apiMethods);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function createApiMethods(): array
    {
        $apiMethods = [];
        foreach ($this->resources as $resource => $resourcePath) {
            $resourceActions = array_filter(array_merge($this->actions, $this->extraActions[$resource] ?? []));
            foreach ($resourceActions as $action => [$httpMethod, $actionPath]) {
                $url = $resourcePath . ($actionPath ? '/' . trim($actionPath) : '') . $this->suffix;
                $method = $action . (in_array($action, $this->pluralize, true) ? Inflector::pluralize($resource) : $resource);
                $apiMethods[$method] = [$httpMethod, $url];
            }
        }
        return $apiMethods;
    }

    /**
     * @param $method
     * @return null|string
     * @inheritdoc
     */
    public function getResourceByMethod($method): ?string
    {
        foreach ($this->actions as $action => $v) {
            if (strpos($method, $action) === 0) {
                $resource = substr($method, strlen($action));
                return in_array($action, $this->pluralize, true) ? Inflector::singularize($resource) : $resource;
            }
        }
        return null;
    }

    /**
     * @param $path
     * @return array
     * @inheritdoc
     */
    public function getPathParams($path): array
    {
        if (preg_match_all('/{([^{}\s]+)}/', $path, $matches)) {
            return $matches[1];
        }
        return [];
    }

    /**
     * @param $path
     * @param $params
     * @inheritdoc
     */
    public function getRealPath($path, $params)
    {
        $pathParams = $this->getPathParams($path);
        if ($pathParams && $params) {
            $pathParamValues = [];
            foreach ($pathParams as $pathParam) {
                $pathParamValues['{' . $pathParam . '}'] = $params[$pathParam];
            }
            return strtr($path, $pathParamValues);
        }
        return $path;
    }

    /**
     * @param string $name
     * @param array $params
     * @return array|mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (isset($this->apiMethods[$name])) {
            return $this->callApiMethod($name, $params[0] ?? []);
        }

        if (strpos($name, 'each') === 0) {
            return $this->each(substr($name, 4), $params[0] ?? []);
        }

        parent::__call($name, $params);
    }

    /**
     * @param $name
     * @param $data
     * @return array
     * @throws NotFoundHttpException
     * @inheritdoc
     */
    public function callApiMethod($name, $data): array
    {
        if (isset($this->apiMethods[$name])) {
            [$method, $url] = $this->apiMethods[$name];
            $method = strtoupper($method);
            $url = $this->getRealPath($url, $data);

            return $this->api($url, $method, $data);
        }
        throw new NotFoundHttpException("API method {$name} not found.");
    }

    /**
     * @param $resource
     * @param $data
     * @return \Iterator
     * @inheritdoc
     */
    abstract public function each($resource, $data): \Iterator;

    /**
     * @return string
     * @inheritdoc
     */
    public function generateMethodDoc(): string
    {
        $apiMethods = [];
        foreach ($this->resources as $resource => $resourcePath) {
            $resourceActions = array_filter(array_merge($this->actions, $this->extraActions[$resource] ?? []));
            foreach ($resourceActions as $action => [$httpMethod, $actionPath]) {
                $url = $resourcePath . ($actionPath ? '/' . trim($actionPath) : '') . $this->suffix;
                $pathParams = $this->getPathParams($url);
                $method = $action . (in_array($action, $this->pluralize, true) ? Inflector::pluralize($resource) : $resource);
                if ($action === 'list') {
                    if (empty($pathParams)) {
                        $apiMethods[] = " * @method array {$method}(\$data = [])";
                        $apiMethods[] = " * @method \Generator each{$resource}(\$data = [])";
                    } else {
                        $apiMethods[] = " * @method array {$method}(\$data)";
                        $apiMethods[] = " * @method \Generator each{$resource}(\$data)";
                    }
                } else {
                    $apiMethods[] = " * @method array {$method}(\$data)";
                }
            }
            $apiMethods[] = ' *';
        }
        return implode("\n", $apiMethods);
    }
}
