<?php

namespace tests\unit;

use lujie\data\loader\ArrayDataLoader;
use lujie\data\loader\CachedDataLoader;

/**
 * Class CachedDataLoaderTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CachedDataLoaderTest extends \Codeception\Test\Unit
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
            'aaa' => 'aaa',
            'bbb' => [
                'ddd' => 'ddd'
            ],
        ];
        $dataLoader = new CachedDataLoader([
            'dataLoader' => new ArrayDataLoader([
                'data' => $data
            ])
        ]);
        $cache = $dataLoader->cache;
        $cache->flush();

        $this->assertEquals($data, $dataLoader->all());
        $this->assertEquals($data['aaa'], $dataLoader->get('aaa'));
        $this->assertEquals($data['bbb']['ddd'], $dataLoader->get('bbb.ddd'));
        $this->assertEquals(null, $dataLoader->get('ccc'));

        $all = $cache->get($dataLoader->cacheAllKey);
        $this->assertEquals($data, $all);
        $this->assertEquals($data['aaa'], $cache->get($dataLoader->cacheKeyPrefix . 'aaa'));
        $this->assertEquals($data['bbb']['ddd'], $cache->get($dataLoader->cacheKeyPrefix . 'bbb.ddd'));
    }
}
