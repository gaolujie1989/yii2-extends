<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\caching;

use lujie\extend\tests\unit\mocks\MockCacheObject;
use Yii;
use yii\caching\TagDependency;

class CachingTraitTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        Yii::$app->cache->flush();
        $cacheObj = new MockCacheObject();
        $value = $cacheObj->getOrSet('cacheKeyAbc', static function () {
            return 'cacheValueAbc';
        });
        $this->assertEquals('cacheValueAbc', $value);
        $this->assertEquals('cacheValueAbc', $cacheObj->cache->get('test:cacheKeyAbc'));
        $cacheObj->cache->set('test:cacheKeyAbc', 'bcd');
        $value = $cacheObj->getOrSet('cacheKeyAbc', static function () {
            return 'abc';
        });
        $this->assertEquals('bcd', $value);

        $cacheObj->flush();
        $this->assertEquals(false, $cacheObj->cache->get('test:test_abc'));

        $this->assertEquals(60, $cacheObj->getDuration());
        $this->assertEquals('test:', $cacheObj->getKeyPrefix());
        $this->assertInstanceOf(TagDependency::class, $cacheObj->getDependency());
        $this->assertEquals(['test'], $cacheObj->getDependency()->tags);

        $cacheObj->setCacheDuration(300);
        $this->assertEquals(300, $cacheObj->getDuration());

        $cacheObj->setCacheKeyPrefix('unitTest:');
        $this->assertEquals('unitTest:', $cacheObj->getKeyPrefix());

        $cacheTags = ['test', 'unitTest'];
        $cacheObj->setCacheTags($cacheTags);
        $this->assertInstanceOf(TagDependency::class, $cacheObj->getDependency());
        $this->assertEquals($cacheTags, $cacheObj->getDependency()->tags);

        $dependency = new TagDependency(['tags' => ['ttt']]);
        $cacheObj->setCacheDependency($dependency);
        $this->assertEquals(['ttt'], $cacheObj->getDependency()->tags);
    }
}
