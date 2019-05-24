<?php

namespace lujie\data\loader\tests\unit;

use lujie\data\loader\ArrayDataLoader;
use lujie\data\loader\ObjectDataLoader;

/**
 * Class ObjectedDataLoaderTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ObjectedDataLoaderTest extends \Codeception\Test\Unit
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
        $data = [
            'aaa' => ['value' => 'aaa'],
            'bbb' => ['value' => ['ddd' => 'ddd']],
        ];
        $dataLoader = new ObjectDataLoader([
            'dataLoader' => new ArrayDataLoader([
                'data' => $data
            ]),
            'objectClass' => DataObject::class,
        ]);

        $this->assertCount(2, $dataLoader->all());
        $this->assertEquals(new DataObject(['value' => 'aaa']), $dataLoader->get('aaa'));
        $this->assertEquals(new DataObject(['value' => ['ddd' => 'ddd']]), $dataLoader->get('bbb'));
        $this->assertNull($dataLoader->get('ccc'));
    }
}
