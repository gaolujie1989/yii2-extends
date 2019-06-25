<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit\pipelines;

use lujie\data\exchange\pipelines\ActiveRecordPipeline;
use lujie\data\exchange\tests\unit\fixtures\Migration;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

class ActiveRecordPipelineTest extends \Codeception\Test\Unit
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
        $condition = [
            'version' => [
                'm000000_123456_import_test1',
                'm000000_123456_import_test2',
                'm000000_123456_import_test3'
            ]
        ];
        Migration::deleteAll($condition);
        $query = Migration::find()->andWhere($condition)->asArray();
        $this->assertEquals(0, $query->count());

        $importer = new ActiveRecordPipeline([
            'modelClass' => Migration::class,
            'indexKeys' => ['version'],
            'runValidation' => true,
        ]);
        $this->assertTrue($importer->process($data));
        $effectedRowCounts = [
            ActiveRecordPipeline::AFFECTED_CREATED => 2,
            ActiveRecordPipeline::AFFECTED_UPDATED => 0,
            ActiveRecordPipeline::AFFECTED_SKIPPED => 0,
        ];
        $this->assertEquals($effectedRowCounts, $importer->getAffectedRowCounts());
        $this->assertEmpty($importer->getErrors());
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
            ActiveRecordPipeline::AFFECTED_CREATED => 3,
            ActiveRecordPipeline::AFFECTED_UPDATED => 1,
            ActiveRecordPipeline::AFFECTED_SKIPPED => 1,
        ];
        $this->assertEquals($effectedRowCounts, $importer->getAffectedRowCounts());
        $this->assertEmpty($importer->getErrors());
        $all = $query->all();
        $this->assertEquals($data, $all);
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testErrorLine(): void
    {
        $data = [
            ['version' => 'm000000_123456_import_test1', 'apply_time' => 1],
            ['version' => 'm000000_123456_import_test2', 'apply_time' => 'abc'],
        ];
        $condition = [
            'version' => [
                'm000000_123456_import_test1',
                'm000000_123456_import_test2',
                'm000000_123456_import_test3'
            ]
        ];
        Migration::deleteAll($condition);
        $query = Migration::find()->andWhere($condition)->asArray();
        $this->assertEquals(0, $query->count());

        $importer = new ActiveRecordPipeline([
            'modelClass' => Migration::class,
            'indexKeys' => ['version'],
            'runValidation' => true,
        ]);
        $this->assertFalse($importer->process($data));
        $errors = $importer->getErrors();
        $this->assertTrue(isset($errors[2]['apply_time']));
    }
}

