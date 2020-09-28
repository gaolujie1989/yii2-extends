<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\filters;

use lujie\extend\caching\CachingTrait;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\Request;

/**
 * Class RateLimitTrait
 * @package lujie\extend\filters
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait RateLimitTrait
{
    use CachingTrait;

    /**
     * @var int
     */
    public $rateLimit = 300;
    /**
     * @var int
     */
    public $rateWindow = 60;
    /**
     * @var string
     */
    public $rateKey = 'default';
    /**
     * @var array
     */
    public $rateLimits = [];
    /**
     * @var int
     */
    public $allowance = 0;
    /**
     * @var int
     */
    public $allowance_updated_at = 0;
    /**
     * @var string
     */
    public $cacheKeyPrefix = 'RateLimit:';
    /**
     * @var string[]
     */
    public $cacheTags = ['RateLimit'];

    /**
     * @param Request $request
     * @param Action $action
     * @return array
     * @inheritdoc
     */
    public function getRateLimit($request, $action)
    {
        if ($this->rateLimits) {
            if (empty($this->rateLimits['default'])) {
                $this->rateLimits['default'] = [$this->rateLimit, $this->rateWindow, $this->rateKey];
            }
            $readOrWrite = $request->getIsGet() ? 'read' : 'write';
            $value = $this->rateLimits[$action->getUniqueId()]
                ?? $this->rateLimits[$readOrWrite]
                ?? $this->rateLimits['default'];

            $this->rateLimit = $value['rateLimit'];
            $this->rateWindow = $value['rateWindow'];
            $this->rateKey = $value['rateKey'];
        }
        // $rateLimit requests per $rateWindows seconds
        return [$this->rateLimit, $this->rateWindow];
    }

    /**
     * @param Request $request
     * @param Action $action
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function loadAllowance($request, $action)
    {
        $cacheKey = $this->rateKey . ':' . ($this->getId() ?: 0);
        if ($value = $this->getCacheValue($cacheKey)) {
            [$this->allowance, $this->allowance_updated_at] = $value;
        }
        return [$this->allowance, $this->allowance_updated_at];
    }

    /**
     * @param Request $request
     * @param Action $action
     * @param int $allowance
     * @param int $timestamp
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $cacheKey = $this->rateKey . ':' . ($this->getId() ?: 0);
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->setCacheValue($cacheKey, [$this->allowance, $this->allowance_updated_at]);
    }
}