<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend;

use lujie\extend\helpers\MemoryHelper;
use lujie\extend\httpclient\JsonFormatter;
use lujie\extend\httpclient\Response;
use lujie\extend\rest\DeleteAction;
use lujie\extend\validators\DateValidator;
use lujie\extend\validators\LinkerValidator;
use lujie\extend\validators\NumberValidator;
use lujie\extend\validators\SkipValidator;
use lujie\extend\validators\StringValidator;
use Yii;
use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\console\Application as YiiConsoleApplication;
use yii\data\Pagination;
use yii\data\Sort;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\MockTransport;
use yii\rest\DeleteAction as YiiDeleteAction;
use yii\rest\Serializer;
use yii\rest\UrlRule;
use yii\validators\DateValidator as YiiDateValidator;
use yii\validators\NumberValidator as YiiNumberValidator;
use yii\validators\StringValidator as YiiStringValidator;
use yii\validators\Validator;

/**
 * Class GlobalInitBootstrap
 * @package lujie\extend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExtendInitBootstrap extends BaseObject implements BootstrapInterface
{
    /**
     * @var string
     */
    public $memoryLimit = '256M';

    /**
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Validator::$builtInValidators['linker'] = LinkerValidator::class;
        Validator::$builtInValidators['skip'] = SkipValidator::class;
        $this->setDefinitions();
        if ($this->memoryLimit) {
            MemoryHelper::setMemoryLimit($this->memoryLimit);
        }
        $app->on(Application::EVENT_BEFORE_REQUEST, [$this, 'setRequestId']);
    }

    public function setDefinitions(): void
    {
        Yii::$container->setDefinitions([
            YiiDeleteAction::class => DeleteAction::class,
            YiiDateValidator::class => DateValidator::class,
            YiiStringValidator::class => StringValidator::class,
            YiiNumberValidator::class => NumberValidator::class,
            //BatchQueryResult::class => SortableBatchQueryResult::class,
//            YiiEmailTarget::class => EmailTarget::class,

            Pagination::class => [
                'pageSizeParam' => 'limit',
                'pageSizeLimit' => [0, 500],
            ],
            Sort::class => [
                'enableMultiSort' => true
            ],
            Serializer::class => [
                'collectionEnvelope' => 'items',
                'linksEnvelope' => 'links',
                'metaEnvelope' => 'meta',
            ],
            UrlRule::class => [
                'tokens' => [
                    '{id}' => '<id:\\d[\\d,]*>',
                    '{ids}' => '<ids:\\d[\\d,;]*>',
                    '{key}' => '<key:\\w+>',
                    '{type}' => '<type:\\w+>',
                ],
                'patterns' => [
                    'PUT,PATCH {id}' => 'update',
                    'DELETE {id}' => 'delete',
                    'GET,HEAD {id}' => 'view',
                    'POST' => 'create',
                    'GET,HEAD' => 'index',
                    '{id}' => 'options',
                    '' => 'options',

                    'POST upload' => 'upload',
                    'POST import' => 'import',
                    'GET,HEAD template' => 'template',
                    'GET,HEAD export' => 'export',
                    'GET,HEAD {id}/download' => 'download',
                    'GET,HEAD download/{id}' => 'download',

                    'PUT,PATCH {ids}/batch' => 'batch-update',
                    'DELETE {ids}/batch' => 'batch-delete',
                    'PUT,PATCH batch' => 'batch-update',
                    'DELETE batch' => 'batch-delete',

                    'POST {id}/prev' => 'move-prev',
                    'POST {id}/next' => 'move-next',

                    'GET,HEAD totals' => 'totals',
                    'GET,HEAD counts' => 'counts',
                    'GET,HEAD statistics' => 'statistics',
                    'GET,HEAD dashboard' => 'dashboard',
                ]
            ],
        ]);
        if (YII_ENV_TEST && Yii::$app instanceof YiiConsoleApplication) {
            Yii::$container->setDefinitions([
                Client::class => [
                    'transport' => MockTransport::class,
                ],
            ]);
        } else {
            $curlOptions = [
//                CURLOPT_SSL_VERIFYHOST => 0,
//                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_TIMEOUT => 75,
            ];
            Yii::$container->setDefinitions([
                Client::class => [
                    'transport' => CurlTransport::class,
                    'requestConfig' => [
                        'options' => $curlOptions
                    ],
                    'responseConfig' => [
                        'class' => Response::class,
                    ],
                    'formatters' => [
                        Client::FORMAT_JSON => JsonFormatter::class,
                    ]
                ],
            ]);
        }
    }

    /**
     * @throws \yii\base\Exception
     */
    public function setRequestId(): void
    {
        Yii::$app->params['requestId'] = Yii::$app->security->generateRandomString();
    }
}
