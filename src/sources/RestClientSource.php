<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;


use Iterator;
use lujie\extend\authclient\RestOAuth2Client;
use yii\base\BaseObject;
use yii\base\NotSupportedException;
use yii\di\Instance;

/**
 * Class ClientSource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RestClientSource extends BaseObject implements BatchSourceInterface, ConditionSourceInterface
{
    /**
     * @var RestOAuth2Client
     */
    public $client;

    /**
     * @var string
     */
    public $resource;

    /**
     * @var array
     */
    public $defaultCondition = [];

    /**
     * @var array
     */
    public $condition = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->client = Instance::ensure($this->client);
    }

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function batch(int $batchSize = 100): Iterator
    {
        $condition = array_merge($this->defaultCondition, $this->condition);
        return $this->client->batch($this->resource, $condition, $batchSize);
    }

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function each(int $batchSize = 100): Iterator
    {
        $iterator = $this->batch($batchSize);
        foreach ($iterator as $items) {
            yield from $items;
        }
    }

    /**
     * @return array
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function all(): array
    {
        throw new NotSupportedException('The "all" method is not supported for RestApiSource');
    }

    /**
     * @param $condition
     * @inheritdoc
     */
    public function setCondition(array $condition): void
    {
        $this->condition = $condition;
    }
}
