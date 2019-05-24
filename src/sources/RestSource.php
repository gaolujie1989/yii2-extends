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
 * Class RestApiSource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RestSource extends BaseObject implements BatchSourceInterface, ConditionSourceInterface
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
    public $condition = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->client = Instance::ensure($this->client, RestOAuth2Client::class);
    }

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function batch($batchSize = 100): Iterator
    {
        return $this->client->each($this->resource, $this->condition, $batchSize);
    }

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function each($batchSize = 100): Iterator
    {
        return $this->client->each($this->resource, $this->condition, $batchSize);
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
    public function setCondition($condition): void
    {
        $this->condition = $condition;
    }
}
