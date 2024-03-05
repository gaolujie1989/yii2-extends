<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\compressors\CompressorInterface;
use lujie\extend\compressors\GzCompressor;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\queue\serializers\JsonSerializer;
use yii\queue\serializers\SerializerInterface;

/**
 * Class CompressReader
 * @package lujie\extend\file\readers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CompressReader extends BaseFileReader
{
    /**
     * @var SerializerInterface
     */
    public $serializer = [
        'class' => JsonSerializer::class,
        'classKey' => null,
    ];

    /**
     * @var ?CompressorInterface
     */
    public $compressor = GzCompressor::class;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->serializer = Instance::ensure($this->serializer, SerializerInterface::class);
        if ($this->compressor) {
            $this->compressor = Instance::ensure($this->compressor, CompressorInterface::class);
        }
    }

    /**
     * @param string $content
     * @return array
     * @inheritdoc
     */
    public function readContent(string $content): array
    {
        if ($this->compressor) {
            $content = $this->compressor->unCompress($content);
        }
        return (array)$this->serializer->unserialize($content);
    }
}
