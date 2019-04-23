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
     * @var string
     */
    public $uniqueKey = 'id';

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
     * @param $data
     * @return mixed|void
     * @inheritdoc
     */
    public function save($data)
    {
        $fileData = $this->dataParser->parseFile($this->file);
        $fileData[$data[$this->uniqueKey]] = $data;
        $this->dataExporter->exportToFile($this->file, $fileData);
    }
}
