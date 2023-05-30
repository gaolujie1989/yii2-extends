<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\sales\channel;

use lujie\data\exchange\pipelines\DbPipeline;
use lujie\data\exchange\pipelines\PipelineInterface;
use lujie\data\storage\DataStorageInterface;
use lujie\extend\compressors\CompressorInterface;
use lujie\extend\compressors\GzCompressor;
use lujie\extend\helpers\ValueHelper;
use lujie\sales\channel\models\SalesChannelOrderData;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\di\Instance;
use yii\helpers\Json;

/**
 * Class SalesChannelOrderDataStorage
 * @package lujie\sales\channel
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelOrderDataStorage extends BaseObject implements DataStorageInterface
{
    /**
     * @var CompressorInterface
     */
    public $compressor = GzCompressor::class;

    /**
     * @var PipelineInterface
     */
    public $pipeline = [
        'class' => DbPipeline::class,
        'modelClass' => SalesChannelOrderData::class,
        'indexKeys' => ['sales_channel_account_id', 'external_order_key']
    ];

    /**
     * @var int
     */
    public $salesChannelAccountId;

    /**
     * @var string
     */
    public $externalOrderKeyField = '';

    /**
     * @var string
     */
    public $externalOrderNoField = '';

    /**
     * @var string
     */
    public $externalOrderCreatedAtField = '';

    /**
     * @var string
     */
    public $externalOrderUpdatedAtField = '';


    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->compressor) {
            $this->compressor = Instance::ensure($this->compressor, CompressorInterface::class);
        }
        if (empty($this->salesChannelAccountId)) {
            throw new InvalidConfigException('The "sales_account_id" property must be set.');
        }
        if (empty($this->externalOrderKeyField)) {
            throw new InvalidConfigException('The "sales_account_id" property must be set.');
        }
        $this->pipeline = Instance::ensure($this->pipeline, PipelineInterface::class);
    }

    /**
     * @param array $keys
     * @return array
     * @inheritdoc
     */
    public function multiGet(array $keys): array
    {
        $orders = SalesChannelOrderData::find()
            ->salesChannelAccountId($this->salesChannelAccountId)
            ->externalCreatedAtBetween($keys[0], $keys[1])
            ->asArray()
            ->select(['order_data'])
            ->column();
        if ($this->compressor) {
            foreach ($orders as $key => $order) {
                $order = $this->compressor->unCompress($order);
                $orders[$key] = Json::decode($order);
            }
        }
        return $orders;
    }

    /**
     * @param array $values
     * @inheritdoc
     */
    public function multiSet(array $values)
    {
        $orders = [];
        foreach ($values as $value) {
            $orders[] = [
                'sales_channel_account_id' => $this->salesChannelAccountId,
                'external_order_key' => $value[$this->externalOrderKeyField] ?? '',
                'external_order_no' => $value[$this->externalOrderNoField] ?? '',
                'external_created_at' => ValueHelper::formatDateTime($value[$this->externalOrderCreatedAtField] ?? 0),
                'external_updated_at' => ValueHelper::formatDateTime($value[$this->externalOrderUpdatedAtField] ?? 0),
            ];
        }
        $this->pipeline->process($orders);
    }

    #region NotSupported methods

    /**
     * @param $key
     * @return mixed
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function get($key)
    {
        throw new NotSupportedException();
    }

    /**
     * @return array|null
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function all(): ?array
    {
        throw new NotSupportedException();
    }

    /**
     * @param $batchSize
     * @return \Iterator
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function batch($batchSize = 100): \Iterator
    {
        throw new NotSupportedException();
    }

    /**
     * @param $batchSize
     * @return \Iterator
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function each($batchSize = 100): \Iterator
    {
        throw new NotSupportedException();
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function set($key, $value)
    {
        throw new NotSupportedException();
    }

    /**
     * @param $key
     * @return mixed
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function remove($key)
    {
        throw new NotSupportedException();
    }

    /**
     * @param array $keys
     * @return mixed
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function multiRemove(array $keys)
    {
        throw new NotSupportedException();
    }

    #endregion
}
