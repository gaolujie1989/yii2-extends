<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit\pipelines;

use lujie\data\exchange\pipelines\DbPipeline;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;

class DbPipelineTest extends \Codeception\Test\Unit
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
        $data = [
            ['version' => 'm000000_123456_import_test1', 'apply_time' => 1, 'notExist' => ''],
            ['version' => 'm000000_123456_import_test2', 'apply_time' => 2],
        ];
        /** @var Connection $db */
        $db = Instance::ensure('db');
        $table = '{{%migration}}';
        $condition = [
            'version' => [
                'm000000_123456_import_test1',
                'm000000_123456_import_test2',
                'm000000_123456_import_test3'
            ]
        ];
        $db->createCommand()->delete($table, $condition)->execute();

        $query = (new Query())->from($table)->andWhere($condition);
        $this->assertEquals(0, $query->count());

        $importer = new DbPipeline([
            'db' => $db,
            'table' => $table,
            'indexKeys' => ['version']
        ]);
        $this->assertTrue($importer->process($data));
        $effectedRowCounts = [
            DbPipeline::AFFECTED_CREATED => 2,
            DbPipeline::AFFECTED_UPDATED => 0,
            DbPipeline::AFFECTED_SKIPPED => 0,
        ];
        $this->assertEquals($effectedRowCounts, $importer->getAffectedRowCounts());
        $all = $query->all();
        unset($data[0]['notExist']);
        $this->assertEquals($data, $all);

        $data = [
            ['version' => 'm000000_123456_import_test1', 'apply_time' => 1],
            ['version' => 'm000000_123456_import_test2', 'apply_time' => 22],
            ['version' => 'm000000_123456_import_test3', 'apply_time' => 3],
        ];
        $this->assertTrue($importer->process($data));
        $effectedRowCounts = [
            DbPipeline::AFFECTED_CREATED => 3,
            DbPipeline::AFFECTED_UPDATED => 1,
            DbPipeline::AFFECTED_SKIPPED => 1,
        ];
        $this->assertEquals($effectedRowCounts, $importer->getAffectedRowCounts());
        $all = $query->all();
        $this->assertEquals($data, $all);
    }
}
