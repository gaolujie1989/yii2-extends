<?php

namespace lujie\data\storage\tests\unit;

use lujie\data\storage\ActiveRecordDataStorage;
use lujie\data\storage\tests\unit\fixtures\Migration;

/**
 * Class ActiveRecordDataStorageTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordDataStorageTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testMe(): void
    {
        $testMigrationVersion = 'm000000_000000_base_test';
        $data = ['version' => $testMigrationVersion, 'apply_time' => 1];

        $dataStorage = new ActiveRecordDataStorage([
            'modelClass' => Migration::class,
            'returnAsArray' => true,
        ]);

        $this->assertEquals(0, $dataStorage->remove($testMigrationVersion));

        $this->assertTrue($dataStorage->set($testMigrationVersion, $data));
        $version = $dataStorage->get($testMigrationVersion);
        $this->assertIsArray($version);
        $this->assertEquals($testMigrationVersion, $version['version']);

        $dataStorage->returnAsArray = false;
        $version = $dataStorage->get($testMigrationVersion);
        $this->assertInstanceOf(Migration::class, $version);

        $this->assertEquals(1, $dataStorage->remove($testMigrationVersion));
        $this->assertEmpty($dataStorage->get($testMigrationVersion));
    }
}
