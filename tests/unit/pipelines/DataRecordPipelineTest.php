<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\pipelines;

use lujie\data\recording\models\DataRecord;
use lujie\data\recording\models\DataSource;
use lujie\data\recording\pipelines\RecordPipeline;

class DataRecordPipelineTest extends \Codeception\Test\Unit
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
     * @return DataSource
     * @inheritdoc
     */
    protected function getSource(): DataSource
    {
        $source = new DataSource([
            'data_account_id' => 1,
            'name' => 'testSource',
            'type' => 'testType',
        ]);
        $source->save(false);
        return $source;
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        $source = $this->getSource();
        $data = [
            'record' => [
                'data_id' => 123,
                'data_created_at' => 123123,
                'data_updated_at' => 456456,
                'data_additional' => [
                    'x1' => 'xxx11',
                    'y' => 'yy123',
                ]
            ],
        ];
        $pipeline = new RecordPipeline([
            'dataSource' => $source
        ]);
        $pipeline->process([$data]);

        $record = DataRecord::find()
            ->dataAccountId($source->data_account_id)
            ->dataSourceType($source->type)
            ->dataId($data['record']['data_id'])
            ->one();
        $this->assertNotNull($record);
        $this->assertEquals($data['record'], $record->getAttributes(array_keys($data['record'])));
    }
}
