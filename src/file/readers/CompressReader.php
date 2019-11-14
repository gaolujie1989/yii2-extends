<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\compressors\GzCompressor;
use lujie\extend\file\FileReaderInterface;
use lujie\extend\compressors\CompressorInterface;
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
class CompressReader extends BaseObject implements FileReaderInterface
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
        return (array)$this->serializer->unserialize($content);
    }
}
