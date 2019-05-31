<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use creocoder\flysystem\Filesystem;
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
     * @var Filesystem
     */
    public $fs;

    /**
     * @var
     */
    public $localPath = '/tmp/';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fileParser = Instance::ensure($this->fileParser, FileParserInterface::class);
        if ($this->fs) {
            $this->fs = Instance::ensure($this->fs);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function all(): array
    {
        $localPath = $this->localPath . $this->file;
        if ($this->fs) {
            file_put_contents($localPath, $this->fs->read($this->file));
        }
        $data = $this->fileParser->parseFile($localPath);
        unlink($localPath);
        return $data;
    }
}
