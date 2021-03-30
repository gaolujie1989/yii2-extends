<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\tasks;

use lujie\data\loader\ArrayDataLoader;
use lujie\data\recording\models\DataSource;
use lujie\data\recording\tasks\RecordingTask;
use lujie\data\recording\tests\unit\fixtures\DataSourceFixture;
use lujie\data\recording\tests\unit\mocks\MockDataRecorder;
use lujie\extend\constants\ExecStatusConst;
use Yii;
use yii\queue\sync\Queue;

class RecordingTaskTest extends \Codeception\Test\Unit
{


    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function _before()
    {
        Yii::$app->set('dataRecorderLoader', [
            'class' => ArrayDataLoader::class,
            'data' => [
                'MOCK' => [
                    'class' => MockDataRecorder::class
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

    public function _fixtures(): array
    {
        return [
            'dataSource' => DataSourceFixture::class,
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
        $queue = new Queue();
        $recordingTask = new RecordingTask([
            'queue' => $queue,
        ]);
        $this->assertTrue($recordingTask->execute());
        $dataSource1 = DataSource::findOne(1);
        $dataSource2 = DataSource::findOne(2);
        $this->assertEquals(ExecStatusConst::EXEC_STATUS_QUEUED, $dataSource1->last_exec_status);
        $this->assertEquals(ExecStatusConst::EXEC_STATUS_PENDING, $dataSource2->last_exec_status);

        $queue->run();
        $dataSource1->refresh();
        $this->assertEquals(ExecStatusConst::EXEC_STATUS_SUCCESS, $dataSource1->last_exec_status);
        $expected = [
            'created' => 1,
            'skipped' => 0,
            'updated' => 0,
        ];
        $this->assertEquals($expected, $dataSource1->last_exec_result);
    }
}
