<?php

namespace lujie\data\storage\tests\unit;

use lujie\data\storage\DbDataStorage;

/**
 * Class DbDataStorageTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbDataStorageTest extends \Codeception\Test\Unit
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

        $dataStorage = new DbDataStorage([
            'table' => '{{%migration}}'
        ]);

        $this->assertEquals(0, $dataStorage->remove($testMigrationVersion));

        $this->assertEquals(1, $dataStorage->set($testMigrationVersion, $data));
        $version = $dataStorage->get($testMigrationVersion);
        $this->assertIsArray($version);
        $this->assertEquals($testMigrationVersion, $version['version']);

        $this->assertEquals(1, $dataStorage->remove($testMigrationVersion));
        $this->assertEmpty($dataStorage->get($testMigrationVersion));
    }
}
