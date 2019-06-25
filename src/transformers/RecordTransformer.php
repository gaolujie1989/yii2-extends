<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\center\transformers;

use lujie\data\exchange\transformers\TransformerInterface;
use lujie\data\staging\compress\CompressorInterface;
use lujie\data\staging\compress\GzDeflateCompressor;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\queue\serializers\JsonSerializer;
use yii\queue\serializers\SerializerInterface;

/**
 * Class RecordTransformer
 * @package lujie\data\center\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RecordTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @var array
     */
    public $recordConfig = [
        'data_key' => '',
        'data_parent_id' => '',
        'data_additional' => '',
    ];

    /**
     * @var array
     */
    public $defaultConfig = [
        'data_id' => 'id',
        'data_created_at' => 'createdAt',
        'data_updated_at' => 'updatedAt',
    ];

    /**
     * @var SerializerInterface
     */
    public $serializer = JsonSerializer::class;

    /**
     * @var CompressorInterface
     */
    public $compressor = GzDeflateCompressor::class;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->serializer = Instance::ensure($this->serializer, SerializerInterface::class);
        $this->compressor = Instance::ensure($this->compressor, CompressorInterface::class);
    }

    /**
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        $record = [];
        $config = array_merge($this->defaultConfig, $this->recordConfig);
        foreach ($config as $attribute => $item) {
            if ($item && $value = ArrayHelper::getValue($data, $item)) {
                $record[$attribute] = $value;
            }
        }

        $text = $this->serializer->serialize($data);
        $text = $this->compressor->compress($text);
        return [
            'text' => $text,
            'record' => $record,
        ];
    }
}
