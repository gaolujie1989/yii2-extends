<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\writers;

use lujie\extend\compressors\CompressorInterface;
use lujie\extend\compressors\GzCompressor;
use lujie\extend\file\FileWriterInterface;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\queue\serializers\JsonSerializer;
use yii\queue\serializers\SerializerInterface;

/**
 * Class CompressedFileExporter
 * @package lujie\extend\file\writers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CompressWriter extends BaseObject implements FileWriterInterface
{
    /**
     * @var SerializerInterface
     */
    public $serializer = [
        'class' => JsonSerializer::class,
        'classKey' => null,
    ];

    /**
     * @var CompressorInterface
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
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    public function read(string $file): array
    {
        $content = file_get_contents($file);
        if ($this->compressor) {
            $content = $this->compressor->unCompress($content);
        }
        if ($this->serializer) {
            $content = $this->serializer->unserialize($content);
        }

        return $content;
    }

    /**
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    public function write(string $file, array $data): void
    {
        $content = $this->serializer->serialize($data);
        if ($this->compressor) {
            $content = $this->compressor->compress($content);
        }
        file_put_contents($file, $content);
    }
}
