<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\tasks;


use lujie\data\loader\ArrayDataLoader;
use lujie\data\recording\models\DataSource;
use lujie\data\recording\tasks\GenerateSourceTask;
use lujie\data\recording\tests\unit\fixtures\DataAccountFixture;
use lujie\data\recording\tests\unit\fixtures\DataSourceFixture;
use lujie\data\recording\tests\unit\mocks\MockDataSourceGenerator;
use lujie\extend\constants\ExecStatusConst;
use Yii;
use yii\queue\sync\Queue;

class GenerateSourceTaskTest extends \Codeception\Test\Unit
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
        Yii::$app->set('dataSourceGeneratorLoader', [
            'class' => ArrayDataLoader::class,
            'data' => [
                'MOCK' => [
                    'class' => MockDataSourceGenerator::class
                ]
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
        ];
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $recordingTask = new GenerateSourceTask([
            'sourceGeneratorLoader' => 'dataSourceGeneratorLoader',
            'sourceTypes' => [
                'MOCK_TYPE1', 'MOCK_TYPE2'
            ],
            'timePeriodSeconds' => 300,
        ]);
        $this->assertTrue($recordingTask->execute());
        $count = DataSource::find()->dataAccountId(1)->type(['MOCK_TYPE1', 'MOCK_TYPE2'])->count();
        $this->assertEquals(2, $count);
        $count = DataSource::find()->dataAccountId(2)->type(['MOCK_TYPE1', 'MOCK_TYPE2'])->count();
        $this->assertEquals(0, $count);
    }
}
