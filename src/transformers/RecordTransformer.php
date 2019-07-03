<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging\transformers;

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
 * @package lujie\data\staging\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RecordTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @var array
     */
    public $recordConfig = [
    ];

    /**
     * @var array
     */
    public $defaultConfig = [
        'data_id' => 'id',
        'data_created_at' => 'createdAt',
        'data_updated_at' => 'updatedAt',
        'data_key' => '',
        'data_parent_id' => '',
        'data_additional' => [],
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
        return array_map(function($values) {
            $record = [];
            $config = array_merge($this->defaultConfig, $this->recordConfig);
            foreach ($config as $attribute => $item) {
                if (empty($item)) {
                    continue;
                }
                if (is_string($item) && $value = ArrayHelper::getValue($values, $item)) {
                    $record[$attribute] = $value;
                } else if (is_array($item)) {
                    foreach ($item as $k => $v) {
                        if (is_int($k)) {
                            $k = $v;
                        }
                        $record[$attribute][$k] = ArrayHelper::getValue($values, $v);
                    }
                }
            }

            $text = $this->serializer->serialize($values);
            $text = $this->compressor->compress($text);
            return [
                'text' => $text,
                'record' => $record,
            ];
        }, $data);
    }
}
