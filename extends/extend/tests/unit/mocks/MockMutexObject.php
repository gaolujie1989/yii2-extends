<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use lujie\extend\mutex\LockingTrait;
use yii\base\BaseObject;

/**
 * Class MockCacheObject
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockMutexObject extends BaseObject
{
    use LockingTrait;

    /**
     * @var int
     */
    public $lockTimeout = 0;

    /**
     * @var string
     */
    public $lockKeyPrefix = 'test:';

    /**
     * @return int
     * @inheritdoc
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getKeyPrefix(): string
    {
        return $this->keyPrefix;
    }
}
