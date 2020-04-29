<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\caching;

use lujie\extend\caching\ChainedCache;
use yii\caching\ArrayCache;
use yii\caching\FileCache;

/**
 * Class ChainedCacheTest
 * @package lujie\extend\tests\unit\caching
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChainedCacheTest extends \Codeception\Test\Unit
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

    /**
     * @inheritdoc
     */
    public function testMe(): void
    {
        $chainedCache = new ChainedCache([
            'caches' => [
                'array' => [
                    'class' => ArrayCache::class,
                ],
                'file' => [
                    'class' => FileCache::class,
                ],
            ],
        ]);

        $arrayCache = $chainedCache->caches['array'];
        $fileCache = $chainedCache->caches['file'];

        $key = 'testKey';
        $value = 'testValue';

        //test set
        $this->assertTrue($chainedCache->set($key, $value));
        $this->assertEquals($value, $chainedCache->get($key));
        $this->assertEquals($value, $arrayCache->get($key));
        $this->assertEquals($value, $fileCache->get($key));

        $this->assertTrue($chainedCache->exists($key));
        $this->assertTrue($arrayCache->exists($key));
        $this->assertTrue($fileCache->exists($key));

        //test delete
        $this->assertTrue($chainedCache->delete($key));
        $this->assertFalse($chainedCache->get($key));
        $this->assertFalse($arrayCache->get($key));
        $this->assertFalse($fileCache->get($key));

        $this->assertFalse($chainedCache->exists($key));
        $this->assertFalse($arrayCache->exists($key));
        $this->assertFalse($fileCache->exists($key));

        //test multi
        $items = [
            'testKey1' => 'testValue1',
            'testKey2' => 'testValue2',
            'testKey3' => 'testValue3',
        ];
        $keys = array_keys($items);
        $this->assertEmpty($chainedCache->multiSet($items));
        $this->assertEquals($items, $chainedCache->multiGet($keys));
        $this->assertEquals($items, $arrayCache->multiGet($keys));
        $this->assertEquals($items, $fileCache->multiGet($keys));
    }
}
