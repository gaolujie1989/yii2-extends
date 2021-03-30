<?php

namespace lujie\data\loader\tests\unit;

use lujie\data\loader\ArrayDataLoader;
use lujie\data\loader\ObjectDataLoader;
use lujie\data\loader\tests\unit\mocks\MockObject;

/**
 * Class ObjectedDataLoaderTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ObjectDataLoaderTest extends \Codeception\Test\Unit
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
        $data = [
            'aaa' => ['value' => 'aaa'],
            'bbb' => ['value' => ['ddd' => 'ddd']],
        ];
        $dataLoader = new ObjectDataLoader([
            'dataLoader' => new ArrayDataLoader([
                'data' => $data
            ]),
            'objectClass' => MockObject::class,
        ]);

        $this->assertCount(2, $dataLoader->all());
        $this->assertEquals(new MockObject(['value' => 'aaa']), $dataLoader->get('aaa'));
        $this->assertEquals(new MockObject(['value' => ['ddd' => 'ddd']]), $dataLoader->get('bbb'));
        $this->assertNull($dataLoader->get('ccc'));

        $data = [
            'aaa' => ['xxx' => 'aaa'],
            'bbb' => ['xxx' => ['ddd' => 'ddd']],
        ];
        $dataLoader = new ObjectDataLoader([
            'dataLoader' => new ArrayDataLoader([
                'data' => $data
            ]),
            'objectClass' => MockObject::class,
            'dataConfig' => [
                'value' => 'xxx',
            ]
        ]);

        $this->assertCount(2, $dataLoader->all());
        $this->assertEquals(new MockObject(['value' => 'aaa']), $dataLoader->get('aaa'));
        $this->assertEquals(new MockObject(['value' => ['ddd' => 'ddd']]), $dataLoader->get('bbb'));
    }
}
