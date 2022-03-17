<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use Iterator;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Trait BatchTrait
 * @package lujie\extend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait BatchIteratorTrait
{
    public $requestPageKey = 'page';

    public $requestPageSizeKey = 'limit';

    public $responseDataKey = 'data';

    public $responsePageKey = 'page';

    public $responsePageCountKey = 'pageCount';

    public $responsePageSizeKey = 'pageSize';

    public $responseTotalCountKey = 'totalCount';

    public $responseNextLinksKey = 'links.next';

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
        return ArrayHelper::getValue($responseData, $this->responseDataKey, $responseData);
    }

    /**
     * @param array $responseData
     * @return int
     * @throws \Exception
     * @inheritdoc
     */
    protected function getPageCount(array $responseData): int
    {
        return $this->responsePageCountKey
            ? ArrayHelper::getValue($responseData, $this->responsePageCountKey, 1)
            : (int)(ArrayHelper::getValue($responseData, $this->responseTotalCountKey, 0)
                / ArrayHelper::getValue($responseData, $this->responsePageSizeKey, 1));
    }

    /**
     * @param array $responseData
     * @return string|null
     * @throws \Exception
     * @inheritdoc
     */
    protected function getNextPageLink(array $responseData): ?string
    {
        return ArrayHelper::getValue($responseData, $this->responseNextLinksKey);
    }

    /**
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return Iterator
     * @throws \Exception
     * @inheritdoc
     */
    protected function batchByPage(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        $listMethod = $this->getBatchInternalMethod($resource);
        $pageSizeKey = $this->requestPageSizeKey;
        if ($pageSizeKey) {
            $condition[$pageSizeKey] = $batchSize;
        }
        $pageKey = $this->requestPageKey;
        if ($this->reverse) {
            $responseData = $this->{$listMethod}($condition);
            $firstPageItems = $this->getPageData($responseData);
            $firstPageNum = $condition[$pageKey] ?? 1;

            $pageCount = $this->getPageCount($responseData);

            $condition[$pageKey] = $pageCount;
            while ($condition[$pageKey] > $firstPageNum) {
                $responseData = $this->{$listMethod}($condition);
                $items = $this->getPageData($responseData);

                yield array_reverse($items);

                $condition[$pageKey]--;
            }

            yield array_reverse($firstPageItems);
        } else {
            do {
                $responseData = $this->{$listMethod}($condition);
                yield $this->getPageData($responseData);

                $pageCount = $this->getPageCount($responseData);

                $condition[$pageKey] = $condition[$pageKey] ?? 1;
                $condition[$pageKey]++;
            } while ($condition[$pageKey] <= $pageCount);
        }
    }

    /**
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return \Generator
     * @throws \Exception
     * @inheritdoc
     */
    protected function batchByLinks(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        $listMethod = $this->getBatchInternalMethod($resource);
        $nextPageLink = null;
        do {
            if ($nextPageLink) {
                $urlParts = parse_url($nextPageLink);
                parse_str($urlParts['query'], $condition);
            }
            $responseData = $this->{$listMethod}($condition);
            yield $this->getPageData($responseData);

            $nextPageLink = $this->getNextPageLink($responseData);
        } while ($nextPageLink);
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
        return $this->requestPageKey
            ? $this->batchByPage($resource, $condition, $batchSize)
            : $this->batchByLinks($resource, $condition, $batchSize);
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