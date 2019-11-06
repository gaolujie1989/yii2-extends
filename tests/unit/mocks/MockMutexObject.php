<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;


use lujie\extend\caching\CachingTrait;
use lujie\extend\mutex\LockingTrait;
use yii\base\BaseObject;
use yii\caching\Dependency;

/**
 * Class MockCacheObject
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockMutexObject extends BaseObject
{
    use LockingTrait;

    /**
     * @var string
     */
    public $lockKeyPrefix = 'test:';

    /**
     * @return string
     * @inheritdoc
     */
    public function getKeyPrefix(): string
    {
        return $this->keyPrefix;
    }
}
