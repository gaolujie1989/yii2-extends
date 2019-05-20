<?php

namespace lujie\data\storage\tests\unit;

use lujie\data\loader\DbDataLoader;
use lujie\data\storage\DbDataStorage;
use lujie\data\storage\FileDataStorage;

/**
 * Class DbDataStorageTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbDataStorageTest extends \Codeception\Test\Unit
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

    // tests
    public function testMe(): void
    {
        $testMigrationVersion = 'm000000_000000_base_test';
        $data = ['version' => $testMigrationVersion, 'apply_time' => 1];

        $dataStorage = new DbDataStorage([
            'table' => '{{%migration}}'
        ]);

        $this->assertEquals(0, $dataStorage->delete($testMigrationVersion));

        $this->assertEquals(1, $dataStorage->set($testMigrationVersion, $data));
        $version = $dataStorage->get($testMigrationVersion);
        $this->assertInternalType('array', $version);
        $this->assertEquals($testMigrationVersion, $version['version']);

        $this->assertEquals(1, $dataStorage->delete($testMigrationVersion));
        $this->assertEmpty($dataStorage->get($testMigrationVersion));
    }
}
