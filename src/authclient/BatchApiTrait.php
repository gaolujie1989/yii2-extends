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
     * @param string $resource
     * @return string
     * @inheritdoc
     */
    protected function getBatchInternalMethod(string $resource): string
    {
        return 'list' . ucfirst(Inflector::pluralize($resource));
    }

    /**
     * @param array $responseData
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    protected function getPageData(array $responseData): array
    {
        return $responseData['data'] ?? [];
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
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return Iterator
     * @throws \Exception
     * @inheritdoc
     */
    public function batch(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        $listMethod = $this->getBatchInternalMethod($resource);
        $nextPageCondition = $condition;
        $firstPageItems = [];

        do {
            $responseData = $this->{$listMethod}($nextPageCondition);
            $items = $this->getPageData($responseData);

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
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return Iterator
     * @throws \Exception
     * @inheritdoc
     */
    public function each(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        $iterator = $this->batch($resource, $condition, $batchSize);
        foreach ($iterator as $items) {
            yield from $items;
        }
    }
}