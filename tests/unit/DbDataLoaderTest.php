<?php

namespace tests\unit;

use lujie\data\loader\DbDataLoader;

/**
 * Class DbDataLoaderTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbDataLoaderTest extends \Codeception\Test\Unit
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
        $baseMigrationVersion = 'm000000_000000_base';
        $dataLoader = new DbDataLoader([
            'table' => '{{%migration}}'
        ]);

        $version = $dataLoader->get($baseMigrationVersion);
        $this->assertInternalType('array', $version);
        $this->assertEquals($baseMigrationVersion, $version['version']);
        $this->assertEquals(null, $dataLoader->get('ccc'));

        $all = $dataLoader->all();
        $count = count($all);
        $this->assertTrue($count >= 1);

        $dataLoader->condition = ['version' => $baseMigrationVersion];
        $this->assertCount(1, $dataLoader->all());
    }
}
