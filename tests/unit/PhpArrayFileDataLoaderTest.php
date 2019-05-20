<?php

namespace tests\unit;

use lujie\data\loader\FileDataLoader;

/**
 * Class PhpArrayFileDataLoaderTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PhpArrayFileDataLoaderTest extends \Codeception\Test\Unit
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
        $data = require __DIR__ . '/data.php';
        $dataLoader = new FileDataLoader([
            'filePools' => [__DIR__],
        ]);

        $this->assertEquals($data, $dataLoader->all());
        $this->assertEquals($data['aaa'], $dataLoader->get('aaa'));
        $this->assertEquals($data['bbb']['ddd'], $dataLoader->get('bbb.ddd'));
        $this->assertEquals(null, $dataLoader->get('ccc'));
    }
}
