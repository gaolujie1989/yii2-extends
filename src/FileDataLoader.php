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
    public $filePools = ['@modules', '@common/modules'];

    /**
     * @var string
     */
    public $filePathTemplate = '{filePool}/data.php';

    /**
     * @var FileParserInterface
     */
    public $fileParser;

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
     * @return array|void
     * @inheritdoc
     */
    public function all()
    {
        if (!$this->data) {
            $loadedFiles = $this->loadFiles();
            foreach ($loadedFiles as $loadedFile) {
                $data = $this->fileParser->parseFile($loadedFile);
                $this->data = ArrayHelper::merge($this->data, $data);
            }
        }
        return $this->data;
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function loadFiles()
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
}
