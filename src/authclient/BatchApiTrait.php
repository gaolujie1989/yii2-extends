<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use Iterator;
use yii\base\NotSupportedException;
use yii\helpers\Inflector;

/**
 * Trait BatchTrait
 * @package lujie\extend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait BatchApiTrait
{
    /**
     * @var bool
     */
    public $reverse = false;

    /**
     * @param bool $reverse
     * @return $this
     * @inheritdoc
     */
    public function reverse(bool $reverse = true): self
    {
        $this->reverse = $reverse;
        return $this;
    }

    /**
     * @param string $method
     * @param array $params
     * @return Iterator
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function eachInternal(string $method, array $params = []): Iterator
    {
        $iterator = $this->batchInternal($method, $params);
        foreach ($iterator as $items) {
            yield from $items;
        }
    }

    /**
     * @param string $method
     * @param array $params
     * @return Iterator
     * @throws NotSupportedException
     * @inheritdoc
     */
    protected function batchInternal(string $method, array $params = []): Iterator
    {
        $conditionIndex = count($params) - 1;
        $condition = $params[$conditionIndex] ?? [];
        $nextPageCondition = $condition;
        $firstPageItems = [];

        do {
            $params[$conditionIndex] = $nextPageCondition;
            $responseData = call_user_func_array([$this, $method], $params);
            $items = $this->getPageData($responseData, $method);

            if ($this->reverse) {
                if (empty($firstPageItems)) {
                    $firstPageItems = $items;
                } else {
                    yield array_reverse($firstPageItems);
                }
            } else {
                yield $items;
            }

            $nextPageCondition = $this->getNextPageCondition($responseData, $condition);
        } while ($nextPageCondition);

        if ($this->reverse && $firstPageItems) {
            yield array_reverse($firstPageItems);
        }
    }

    /**
     * @param array $responseData
     * @param string $method
     * @return array
     * @inheritdoc
     */
    protected function getPageData(array $responseData, string $method): array
    {
        return $responseData['data'] ?? [];
    }

    /**
     * @param array $responseData
     * @param array $condition
     * @return array|null
     * @throws NotSupportedException
     * @inheritdoc
     */
    protected function getNextPageCondition(array $responseData, array $condition): ?array
    {
        throw new NotSupportedException('Not supported');
    }

    /**
     * @param array $condition
     * @param string $pageKey
     * @param int $pageCount
     * @param int $currentPage
     * @return array|null
     * @inheritdoc
     */
    protected function getNextByPagination(array $condition, string $pageKey, int $pageCount, int $currentPage): ?array
    {
        if (empty($pageCount) || empty($currentPage)) {
            return null;
        }
        $firstPage = $condition[$pageKey] ?? 1;
        $nextPage = $this->reverse ? $currentPage - 1 : $currentPage + 1;
        if ($nextPage <= $firstPage || $nextPage > $pageCount) {
            return null;
        }
        $condition[$pageKey] = $nextPage;
        return $condition;
    }

    /**
     * @param string $nextLink
     * @return array|null
     * @inheritdoc
     */
    protected function getNextByLink(string $nextLink): ?array
    {
        if (empty($nextLink)) {
            return null;
        }
        $urlParts = parse_url($nextLink);
        parse_str($urlParts['query'], $condition);
        return $condition;
    }

    #region @deprecated methods

    /**
     * @param string $resource
     * @return string
     * @deprecated
     */
    protected function getBatchInternalMethod(string $resource): string
    {
        return 'list' . ucfirst(Inflector::pluralize($resource));
    }

    /**
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return Iterator
     * @throws \Exception
     * @deprecated
     */
    public function batch(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        $listMethod = $this->getBatchInternalMethod($resource);
        return $this->batchInternal($listMethod, [$condition]);
    }

    /**
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return Iterator
     * @throws \Exception
     * @deprecated
     */
    public function each(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        $iterator = $this->batch($resource, $condition);
        foreach ($iterator as $items) {
            yield from $items;
        }
    }

    #endregion
}
