<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit;

use lujie\data\recording\models\DataSource;
use lujie\data\recording\DataSourceModelStorage;

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
        $dataSourceId = $source->data_source_id;

        $storage = new DataSourceModelStorage();
        $this->assertNull($storage->get($dataSourceId));
        $data = ['xxx' => 'xxx'];
        $storage->set($dataSourceId, $data);

        $source = DataSource::findOne($dataSourceId);
        $this->assertTrue(isset($source->additional[$storage->conditionKey]));
        $this->assertEquals($data, $source->additional[$storage->conditionKey]);

        $data = ['yyy' => 'yyy'];
        $storage->set($dataSourceId, $data);
        $source = DataSource::findOne($dataSourceId);
        $this->assertEquals($data, $source->additional[$storage->conditionKey]);

        $storage->remove($dataSourceId);
        $source = DataSource::findOne($dataSourceId);
        $this->assertNull($source->additional[$storage->conditionKey]);
    }
}
