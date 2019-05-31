<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file\exporters;

use lujie\data\exchange\file\FileParserInterface;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

/**
 * Class CompressedFileExporter
 * @package lujie\data\exchange\file\exporters
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CompressedFileParser extends BaseObject implements FileParserInterface
{
    /**
     * @var string
     */
    public $unSerializer = 'yii\helpers\Json::decode';

    /**
     * @var string
     */
    public $unCompressor = 'gzdeflate';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (!is_callable($this->unSerializer)) {
            throw new InvalidConfigException('The unSerializer must be callable');
        }
        if (!is_callable($this->unCompressor)) {
            throw new InvalidConfigException('The unCompressor must be callable');
        }
    }

    /**
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    public function parseFile(string $file): array
    {
        $dataStr = file_get_contents($file);
        $unCompressedStr = call_user_func($this->unCompressor, $dataStr);
        return call_user_func($this->unSerializer, $unCompressedStr);
    }
}
