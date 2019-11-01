<?php

namespace lujie\extend\tests\unit\authclient;

use lujie\extend\authclient\CacheStateStorage;
use Yii;

class CacheStateStorageTest extends \Codeception\Test\Unit
{
    /**
     * @var \lujie\extend\tests\UnitTester
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
        $cache = Yii::$app->getCache();
        $storage = new CacheStateStorage();
        $storage->set('testKey', 'testValue');
        $this->assertEquals('testValue', $storage->get('testKey'));
        $this->assertEquals('testValue', $cache->get('testKey'));

        $storage->remove('testKey');
        $this->assertEquals(false, $storage->get('testKey'));
        $this->assertFalse($cache->exists('testKey'));
    }
}
