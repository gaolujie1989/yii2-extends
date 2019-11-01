<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;


use lujie\extend\caching\CachingTrait;
use yii\base\BaseObject;
use yii\caching\Dependency;

/**
 * Class MockCacheObject
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockCacheObject extends BaseObject
{
    use CachingTrait;

    /**
     * @var int
     */
    public $cacheDuration = 60;

    /**
     * @var string
     */
    public $cacheKeyPrefix = 'test:';

    /**
     * @var
     */
    public $cacheDependency;

    /**
     * @var array
     */
    public $cacheTags = ['test'];

    /**
     * @return int
     * @inheritdoc
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getKeyPrefix(): string
    {
        return $this->keyPrefix;
    }

    /**
     * @return Dependency
     * @inheritdoc
     */
    public function getDependency(): Dependency
    {
        return $this->dependency;
    }
}
