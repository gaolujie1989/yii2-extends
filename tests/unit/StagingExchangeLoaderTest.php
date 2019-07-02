<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit;

use lujie\data\exchange\DataExchange;
use lujie\data\exchange\sources\RestClientSource;
use lujie\data\staging\DataSourceModelStorage;
use lujie\data\staging\models\DataAccount;
use lujie\data\staging\models\DataSource;
use lujie\data\staging\pipelines\ActiveRecordDataRecordPipeline;
use lujie\data\staging\StagingExchangeLoader;
use lujie\data\staging\tests\unit\mocks\IncrementSourceMock;
use lujie\data\staging\transformers\RecordTransformer;
use yii\httpclient\Client;

class StagingExchangeLoaderTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        $account = new DataAccount([
            'name' => 'testAccount',
            'type' => 'testType',
            'options' => [
                'request' => ['xxx' => 'xxx']
            ],
        ]);
        $account->save(false);
        $source = new DataSource([
            'data_account_id' => $account->data_account_id,
            'name' => 'testSource',
            'type' => 'testType',
            'options' => [],
        ]);
        $source->save(false);
        $dataSourceId = $source->data_source_id;

        $loader = new StagingExchangeLoader([
            'clients' => [
                'testType' => [
                    'class' => Client::class,
                    'requestConfig' => 'options.request',
                ]
            ],
            'clientSources' => [
                'testType' => [
                    'resource' => 'testResource',
                ]
            ],
            'incrementSources' => [
                'testType' => [
                    'class' => IncrementSourceMock::class
                ]
            ],
            'transformers' => [
                'testType' => [
                    'class' => RecordTransformer::class,
                ]
            ],
            'pipelines' => [
                'testType' => [
                    'class' => ActiveRecordDataRecordPipeline::class
                ]
            ],
        ]);
        $expectedExchange = new DataExchange([
            'source' => new IncrementSourceMock([
                'dataStorage' => DataSourceModelStorage::class,
                'sourceKey' => $dataSourceId,
                'source' => new RestClientSource([
                    'client' => new Client([
                        'requestConfig' => ['xxx' => 'xxx']
                    ]),
                    'resource' => 'testResource',
                ])
            ]),
            'transformer' => new RecordTransformer(),
            'pipeline' => new ActiveRecordDataRecordPipeline([
                'sourceId' => $dataSourceId
            ]),
        ]);
        $this->assertEquals($expectedExchange, $loader->get($dataSourceId));
    }
}
