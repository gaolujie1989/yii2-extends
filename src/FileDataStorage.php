<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class FileDataStorage
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileDataStorage extends BaseObject implements DataStorageInterface
{
    /**
     * @var string
     */
    public $file = '@runtime/data.php';

    /**
     * @var FileExporterInterface
     */
    public $dataExporter;

    /**
     * @var FileParserInterface
     */
    public $dataParser;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->dataExporter = Instance::ensure($this->dataExporter);
        $this->dataParser = Instance::ensure($this->dataParser);
    }

    /**
     * @param int|string $key
     * @return array|null
     * @inheritdoc
     */
    public function get($key)
    {
        $fileData = $this->dataParser->parseFile($this->file);
        return $fileData[$key];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function all()
    {
        $fileData = $this->dataParser->parseFile($this->file);
        return $fileData;
    }

    /**
     * @param $data
     * @return mixed|void
     * @inheritdoc
     */
    public function set($key, $data)
    {
        $fileData = $this->dataParser->parseFile($this->file);
        $fileData[$key] = $data;
        $this->dataExporter->exportToFile($this->file, $fileData);
    }

    /**
     * @param $key
     * @return mixed|void
     * @inheritdoc
     */
    public function delete($key)
    {
        $fileData = $this->dataParser->parseFile($this->file);
        unset($fileData[$key]);
        $this->dataExporter->exportToFile($this->file, $fileData);
    }
}
