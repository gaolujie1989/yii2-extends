<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend;

use lujie\extend\rest\DeleteAction;
use lujie\extend\validators\DateValidator;
use lujie\extend\validators\LinkerValidator;
use lujie\extend\validators\NumberValidator;
use lujie\extend\validators\StringValidator;
use Yii;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\data\Pagination;
use yii\data\Sort;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
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
     * @param \yii\base\Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Validator::$builtInValidators['linker'] = LinkerValidator::class;
        $this->setDefinitions();
    }

    public function setDefinitions(): void
    {
        Yii::$container->setDefinitions([
            YiiDeleteAction::class => DeleteAction::class,
            YiiDateValidator::class => DateValidator::class,
            YiiStringValidator::class => StringValidator::class,
            YiiNumberValidator::class => NumberValidator::class,
            //BatchQueryResult::class => SortableBatchQueryResult::class,

            Client::class => [
                'transport' => CurlTransport::class,
                'requestConfig' => [
                    'options' => [
                        CURLOPT_SSL_VERIFYHOST => 0,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_CONNECTTIMEOUT => 15,
                        CURLOPT_TIMEOUT => 75,
                    ]
                ]
            ],
            Pagination::class => [
                'pageSizeParam' => 'limit',
                'pageSizeLimit' => [1, 500],
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
                    'GET,HEAD {ids}/batch' => 'batch-download',
                    'POST batch' => 'batch-save',

                    'POST {id}/prev' => 'move-prev',
                    'POST {id}/next' => 'move-next',

                    'GET,HEAD totals' => 'totals',
                    'GET,HEAD counts' => 'counts',
                    'GET,HEAD statistics' => 'statistics',
                ]
            ],
        ]);
    }
}