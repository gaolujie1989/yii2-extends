<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use lujie\extend\file\FileReaderInterface;
use lujie\extend\file\readers\PhpReader;
use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class FileLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileDataLoader extends ArrayDataLoader
{
    /**
     * @var array
     */
    public $filePools = [];

    /**
     * @var string
     */
    public $filePathTemplate = '{filePool}/data.php';

    /**
     * @var FileReaderInterface
     */
    public $fileReader = PhpReader::class;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fileReader = Instance::ensure($this->fileReader, FileReaderInterface::class);
    }

    /**
     * @param int|string $key
     * @return array|mixed|null
     * @inheritdoc
     */
    public function get($key)
    {
        if (empty($this->data)) {
            $this->data = $this->loadFilesData();
        }
        return parent::get($key);
    }

    /**
     * @return array|null
     * @inheritdoc
     */
    public function all(): ?array
    {
        if (empty($this->data)) {
            $this->data = $this->loadFilesData();
        }
        return $this->data;
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function findFiles(): ?array
    {
        $loadedFiles = [];
        foreach ($this->filePools as $filePool) {
            $filePool = Yii::getAlias($filePool);
            $filePath = strtr($this->filePathTemplate, ['{filePool}' => $filePool]);
            $files = glob($filePath);
            $loadedFiles[] = $files;
        }
        return array_unique(array_merge(...$loadedFiles));
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function loadFilesData(): array
    {
        $loadedFiles = $this->findFiles();
        $data = [[], []];
        foreach ($loadedFiles as $loadedFile) {
            $fileData = $this->fileReader->read($loadedFile);
            $data[] = $fileData;
        }
        return ArrayHelper::merge(...$data);
    }
}
