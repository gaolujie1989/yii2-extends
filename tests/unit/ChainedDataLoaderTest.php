<?php

namespace lujie\data\loader\tests\unit;

use lujie\data\loader\ArrayDataLoader;
use lujie\data\loader\ChainedDataLoader;

/**
 * Class ChainedDataLoaderTest
 * @package lujie\data\loader\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChainedDataLoaderTest extends \Codeception\Test\Unit
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
        $dataLoader = new ChainedDataLoader([
            'dataLoaders' => [
                'a' => [
                    'class' => ArrayDataLoader::class,
                    'data' => [
                        'a1' => 'a11',
                        'a2' => 'a22',
                    ]
                ],
                'b' => [
                    'class' => ArrayDataLoader::class,
                    'data' => [
                        'b1' => 'b11',
                        'b2' => 'b22',
                    ]
                ],
            ]
        ]);
        $this->assertEquals('a22', $dataLoader->get('a2'));
        $this->assertEquals('b22', $dataLoader->get('b2'));
        $this->assertNull($dataLoader->get('c2'));
    }
}
