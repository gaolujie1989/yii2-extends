<?php

namespace tests\unit;

use lujie\data\loader\ArrayDataLoader;

/**
 * Class ArrayDataLoaderTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ArrayDataLoaderTest extends \Codeception\Test\Unit
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
    public function testMe()
    {
        $data = [
            'aaa' => 'aaa',
            'bbb' => 'bbb',
        ];
        $dataLoader = new ArrayDataLoader([
            'data' => $data
        ]);

        $this->assertEquals($data, $dataLoader->all());
        $this->assertEquals($data['aaa'], $dataLoader->get('aaa'));
    }
}
