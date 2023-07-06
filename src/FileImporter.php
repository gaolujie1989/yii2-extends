<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;

use lujie\extend\flysystem\Filesystem;
use lujie\data\exchange\sources\FileSource;
use lujie\extend\file\readers\ExcelReader;
use yii\di\Instance;

/**
 * Class FileImporter
 *
 * @property FileSource $source
 *
 * @package lujie\data\exchange
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileImporter extends DataExchanger
{
    /**
     * @var array
     */
    public $fileSource = [
        'fileReader' => ExcelReader::class,
    ];

    /**
     * @param string $file
     * @param Filesystem|null $fs
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function import(string $file, ?Filesystem $fs = null): bool
    {
        $this->prepare($file, $fs);
        return $this->execute();
    }

    /**
     * @param string $file
     * @param Filesystem|null $fs
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function prepare(string $file, ?Filesystem $fs = null): void
    {
        if (is_array($this->fileSource) && empty($this->fileSource['class'])) {
            $this->fileSource['class'] = FileSource::class;
        }
        $this->source = Instance::ensure($this->fileSource, FileSource::class);
        $this->source->file = $file;
        $this->source->fs = $fs;
    }
}
