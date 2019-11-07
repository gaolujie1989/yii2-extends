<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\tasks;


use lujie\data\recording\models\DataSource;
use lujie\data\recording\tasks\RecordingTask;
use lujie\data\recording\tests\unit\fixtures\DataAccountFixture;
use lujie\data\recording\tests\unit\fixtures\DataSourceFixture;
use lujie\extend\constants\ExecStatusConst;
use yii\queue\sync\Queue;

class RecordingTaskTest extends \Codeception\Test\Unit
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
    }
}
