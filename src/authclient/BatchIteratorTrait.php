<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend;

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
        if ($this->requestPageSizeKey) {
            $condition[$this->requestPageSizeKey] = $batchSize;
        }
        if ($this->reverse) {
            $responseData = $this->{$listMethod}($condition);
            $firstPageItems = ArrayHelper::getValue($responseData, $this->responseDataKey, $responseData);
            $firstPageNum = $condition[$this->responsePageKey] ?? 1;

            $pageCount = $this->responsePageCountKey
                ? ArrayHelper::getValue($responseData, $this->responsePageCountKey, 1)
                : (int)(ArrayHelper::getValue($responseData, $this->responseTotalCountKey, 0)
                    / ArrayHelper::getValue($responseData, $this->responsePageSizeKey, $batchSize));

            $condition[$this->requestPageKey] = $pageCount;
            while ($condition[$this->requestPageKey] > $firstPageNum) {
                $responseData = $this->{$listMethod}($condition);
                $items = ArrayHelper::getValue($responseData, $this->responseDataKey, $responseData);

                yield array_reverse($items);

                $condition[$this->requestPageKey]--;
            }

            yield array_reverse($firstPageItems);
        } else {
            do {
                $responseData = $this->{$listMethod}($condition);
                yield ArrayHelper::getValue($responseData, $this->responseDataKey, $responseData);

                $pageCount = $this->responsePageCountKey
                    ? ArrayHelper::getValue($responseData, $this->responsePageCountKey, 1)
                    : (int)(ArrayHelper::getValue($responseData, $this->responseTotalCountKey, 0)
                        / ArrayHelper::getValue($responseData, $this->responsePageSizeKey, $batchSize));

                $condition[$this->requestPageKey] = $condition[$this->requestPageKey] ?? 1;
                $condition[$this->requestPageKey]++;
            } while ($condition[$this->requestPageKey] <= $pageCount);
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