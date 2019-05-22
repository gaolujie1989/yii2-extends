<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use lujie\data\exchange\file\FileParserInterface;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class FileSource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileSource extends BaseObject implements SourceInterface
{
    /**
     * @var FileParserInterface
     */
    public $fileParser;

    /**
     * @var string
     */
    public $file = '/tmp/imports/tmp_import.bin';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fileParser = Instance::ensure($this->fileParser, FileParserInterface::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function all(): array
    {
        return $this->fileParser->parseFile($this->file);
    }
}
