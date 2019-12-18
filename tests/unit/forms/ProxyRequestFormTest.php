<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\forms;


use lujie\data\loader\ArrayDataLoader;
use lujie\data\loader\ChainedDataLoader;
use lujie\data\recording\forms\ProxyRequestForm;
use lujie\data\recording\forms\RecordingForm;
use lujie\data\recording\models\DataRecord;
use lujie\data\recording\models\DataSource;
use lujie\data\recording\tests\unit\fixtures\DataAccountFixture;
use lujie\data\recording\tests\unit\fixtures\DataSourceFixture;
use lujie\data\recording\tests\unit\mocks\MockApiClient;
use lujie\data\recording\tests\unit\mocks\MockClientLoader;
use lujie\data\recording\tests\unit\mocks\MockDataRecorder;
use lujie\extend\constants\ExecStatusConst;
use Yii;
use yii\helpers\Json;

class ProxyRequestFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function _before()
    {
        Yii::$app->set('dataClientLoader', [
            'class' => ChainedDataLoader::class,
            'dataLoaders' => [
                'class' => MockClientLoader::class
            ]
        ]);
    }

    protected function _after()
    {
    }

    public function _fixtures(): array
    {
        return [
            'dataAccount' => DataAccountFixture::class,
            'dataSource' => DataSourceFixture::class,
        ];
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function testSend(): void
    {
        MockApiClient::$responses[] = ['xxx'];
        $proxyRequestForm = new ProxyRequestForm();
        $proxyRequestForm->dataAccountId = 1;
        $proxyRequestForm->url = 'test/xxx';
        $proxyRequestForm->method = 'GET';
        $this->assertTrue($proxyRequestForm->send());
        $this->assertEquals(['xxx'], $proxyRequestForm->responseData);
    }
}
