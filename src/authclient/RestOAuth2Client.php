<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use Iterator;
use Throwable;
use yii\authclient\CacheStateStorage;
use yii\authclient\OAuth2;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
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
        'requestConfig' => [
            'format' => 'json'
        ],
        'responseConfig' => [
            'format' => 'json'
        ],
    ];
}
