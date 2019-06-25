<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit;

use lujie\data\staging\models\DataSource;
use lujie\data\staging\DataSourceModelStorage;

class DataSourceModelStorageTest extends \Codeception\Test\Unit
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
        $source = new DataSource([
            'name' => 'testSource',
            'type' => 'testType',
            'options' => [],
        ]);
        $source->save(false);
        $storage = new DataSourceModelStorage();
        $this->assertNull($storage->get($source->data_source_id));
        $data = ['xxx' => 'xxx'];
        $storage->set($source->data_source_id, $data);

        $source = DataSource::findOne($source->data_source_id);
        $this->assertTrue(isset($source->additional_info[$storage->conditionKey]));
        $this->assertEquals($data, $source->additional_info[$storage->conditionKey]);

        $data = ['yyy' => 'yyy'];
        $storage->set($source->data_source_id, $data);
        $source = DataSource::findOne($source->data_source_id);
        $this->assertEquals($data, $source->additional_info[$storage->conditionKey]);

        $storage->remove($source->data_source_id);
        $source = DataSource::findOne($source->data_source_id);
        $this->assertNull($source->additional_info[$storage->conditionKey]);
    }
}
