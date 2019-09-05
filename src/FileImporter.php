<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;


use lujie\data\exchange\file\readers\ExcelReader;
use lujie\data\exchange\sources\FileSource;
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
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function import(string $file): bool
    {
        if (is_array($this->fileSource) && empty($this->fileSource['class'])) {
            $this->fileSource['class'] = FileSource::class;
        }
        /** @var FileSource $source */
        $this->source = Instance::ensure($this->fileSource, FileSource::class);
        $this->source->file = $file;
        return $this->execute();
    }
}
