<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class FileLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileDataLoader extends ArrayDataLoader implements DataLoaderInterface
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
     * @var FileParserInterface
     */
    public $fileParser = PhpArrayFileParser::class;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->fileParser = Instance::ensure($this->fileParser);
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
     * @return array|void
     * @inheritdoc
     */
    public function all()
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
    protected function findFiles()
    {
        $loadedFiles = [];
        foreach ($this->filePools as $filePool) {
            $filePool = Yii::getAlias($filePool);
            $filePath = strtr($this->filePathTemplate, ['{filePool}' => $filePool]);
            $files = glob($filePath);
            $loadedFiles = array_merge($loadedFiles, $files);
        }
        array_unique($loadedFiles);
        return $loadedFiles;
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function loadFilesData()
    {
        $loadedFiles = $this->findFiles();
        $data = [];
        foreach ($loadedFiles as $loadedFile) {
            $fileData = $this->fileParser->parseFile($loadedFile);
            $data = ArrayHelper::merge($data, $fileData);
        }
        return $data;
    }
}
