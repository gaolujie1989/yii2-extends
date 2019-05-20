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

        $all = $dataLoader->all();
        $count = count($all);
        $this->assertTrue($count >= 1);
        $this->assertInternalType('array', $all);

        $version = $dataLoader->get($baseMigrationVersion);
        $this->assertInternalType('array', $version);
        $applyTime = $dataLoader->get($baseMigrationVersion . '.apply_time');
        $this->assertTrue($version['apply_time'] > 0);
        $this->assertEquals(null, $dataLoader->get('ccc'));
    }
}
