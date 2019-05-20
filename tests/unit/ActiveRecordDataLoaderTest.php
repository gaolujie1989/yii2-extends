<?php

namespace lujie\data\loader\tests\unit;

use lujie\data\loader\ActiveRecordDataLoader;
use lujie\data\loader\tests\unit\fixtures\Migration;

/**
 * Class ActiveRecordDataLoaderTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordDataLoaderTest extends \Codeception\Test\Unit
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
        $dataLoader = new ActiveRecordDataLoader([
            'modelClass' => Migration::class,
            'returnAsArray' => true,
        ]);

        $version = $dataLoader->get($baseMigrationVersion);
        $this->assertInternalType('array', $version);
        $this->assertEquals($baseMigrationVersion, $version['version']);
        $this->assertEmpty($dataLoader->get('ccc'));

        $all = $dataLoader->all();
        $count = count($all);
        $this->assertTrue($count >= 1);

        $dataLoader->condition = ['version' => $baseMigrationVersion];
        $this->assertCount(1, $dataLoader->all());

        $dataLoader->returnAsArray = false;
        $version = $dataLoader->get($baseMigrationVersion);
        $this->assertInstanceOf(Migration::class, $version);
    }
}
