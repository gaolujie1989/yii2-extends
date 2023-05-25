<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\sales\channel;

use lujie\data\storage\ActiveRecordDataStorage;
use lujie\extend\compressors\CompressorInterface;
use lujie\extend\compressors\GzCompressor;
use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\models\SalesChannelOrderData;
use yii\di\Instance;
use yii\helpers\Json;

/**
 * Class SalesChannelOrderDataStorage
 * @package lujie\sales\channel
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelOrderDataStorage extends ActiveRecordDataStorage
{
    public $modelClass = SalesChannelOrderData::class;

    public $key = 'sales_channel_order_id';

    public $value = 'order_data';

    /**
     * @var GzCompressor
     */
    public $compressor = GzCompressor::class;

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
    }

    /**
     * @param $key
     * @return array|mixed|\yii\db\BaseActiveRecord|null
     * @inheritdoc
     */
    public function get($key)
    {
        $value = parent::get($key);
        if ($this->compressor) {
            $value = $this->compressor->unCompress($value);
            $value = Json::decode($value);
        }
        return $value;
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     * @inheritdoc
     */
    public function set($key, $value): bool
    {
        if ($key instanceof SalesChannelOrder) {
            $key = $key->sales_channel_order_id;
        }
        if ($this->compressor) {
            $value = Json::encode($value);
            $value = $this->compressor->compress($value);
        }
        return parent::set($key, $value);
    }
}
