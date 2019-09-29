<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\httpclient;

use Yii;
use yii\base\Behavior;
use yii\httpclient\Client;
use yii\httpclient\RequestEvent;
use yii\web\HeaderCollection;

/**
 * Class RateLimitBehavior
 *
 * @property Client $owner
 *
 * @package lujie\plentyMarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RateLimitCheckerBehavior extends Behavior
{
    /**
     * @var string
     */
    public $limitHeader;

    /**
     * @var string
     */
    public $remainingHeader;

    /**
     * Retry After
     * seconds later on limit reset
     * @var string
     */
    public $resetHeader;

    /**
     * @var int
     */
    public $allowance;

    /**
     * @var int
     */
    public $allowanceUpdatedAt;

    /**
     * @var int
     */
    public $allowanceResetAt;

    /**
     * @var bool
     */
    public $waitOnLimited = true;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            Client::EVENT_BEFORE_SEND => 'beforeSend',
            Client::EVENT_AFTER_SEND => 'afterSend',
        ];
    }

    /**
     * @param RequestEvent $event
     * @inheritdoc
     */
    public function beforeSend(RequestEvent $event): void
    {
        $now = time();
        if ($this->allowance <= 0 && ($now - $this->allowanceUpdatedAt) < $this->allowanceResetAt
            && $this->waitOnLimited) {
            $waitTime = $this->allowanceResetAt - ($now - $this->allowanceUpdatedAt);
            sleep($waitTime);
        }
    }

    /**
     * @param RequestEvent $event
     * @inheritdoc
     */
    public function afterSend(RequestEvent $event): void
    {
        if ($event->response === null) {
            return;
        }
        $headers = $event->response->getHeaders();
        $limit = $this->getRateLimit($headers);
        [$this->allowance, $this->allowanceResetAt] = $this->getAllowance($headers);
        $this->allowanceUpdatedAt = time();
        $message = "Rate Limit: {$this->allowance}/{$limit}, Reset After {$this->allowanceResetAt}."
            . " Url: {$event->request->getFullUrl()}";
        Yii::info($message, __METHOD__);
    }

    /**
     * @param HeaderCollection $headers
     * @return array
     * @inheritdoc
     */
    public function getAllowance(HeaderCollection $headers): array
    {
        return [
            $headers->get($this->remainingHeader),
            $headers->get($this->resetHeader),
        ];
    }

    /**
     * @param HeaderCollection $headers
     * @return int
     * @inheritdoc
     */
    public function getRateLimit(HeaderCollection $headers): ?int
    {
        return $headers->get($this->limitHeader);
    }
}
