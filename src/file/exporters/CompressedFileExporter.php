<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file\exporters;

use lujie\data\exchange\file\FileExporterInterface;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

/**
 * Class CompressedFileExporter
 * @package lujie\data\exchange\file\exporters
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CompressedFileExporter extends BaseObject implements FileExporterInterface
{
    /**
     * @var string
     */
    public $serializer = 'yii\helpers\Json::encode';

    /**
     * @var string
     */
    public $compressor = 'gzinflate';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (!is_callable($this->serializer)) {
            throw new InvalidConfigException('The serializer must be callable');
        }
        if (!is_callable($this->compressor)) {
            throw new InvalidConfigException('The compressor must be callable');
        }
    }

    /**
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    public function exportToFile(string $file, array $data): void
    {
        $dataStr = call_user_func($this->serializer, $data);
        $compressedStr = call_user_func($this->compressor, $dataStr);
        file_put_contents($file, $compressedStr);
    }
}
