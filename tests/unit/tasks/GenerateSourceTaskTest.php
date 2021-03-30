<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\tasks;

use lujie\data\loader\ArrayDataLoader;
use lujie\data\recording\models\DataSource;
use lujie\data\recording\tasks\GenerateSourceTask;
use lujie\data\recording\tests\unit\mocks\MockDataSourceGenerator;
use Yii;

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
        Yii::$app->set('dataAccountLoader', [
            'class' => ArrayDataLoader::class,
            'data' => require __DIR__ . '/../fixtures/data/data_account.php'
        ]);
    }

    protected function _after()
    {
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
                'MOCK' => ['MOCK_TYPE1', 'MOCK_TYPE2']
            ],
            'timeDurationSeconds' => 300,
        ]);
        $this->assertTrue($recordingTask->execute());
        $count = DataSource::find()->dataAccountId(1)->type(['MOCK_TYPE1', 'MOCK_TYPE2'])->count();
        $this->assertEquals(2, $count);
        $count = DataSource::find()->dataAccountId(2)->type(['MOCK_TYPE1', 'MOCK_TYPE2'])->count();
        $this->assertEquals(0, $count);
    }
}
