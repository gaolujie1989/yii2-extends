<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\storage;

use lujie\data\loader\ArrayDataLoader;
use lujie\data\loader\FileParserInterface;
use lujie\data\loader\PhpArrayFileParser;
use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class FileDataStorage
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileDataStorage extends ArrayDataLoader implements DataStorageInterface
{
    /**
     * @var string
     */
    public $file = '@runtime/data.php';

    /**
     * @var FileExporterInterface
     */
    public $dataExporter = PhpArrayFileExporter::class;

    /**
     * @var FileParserInterface
     */
    public $dataParser = PhpArrayFileParser::class;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->dataExporter = Instance::ensure($this->dataExporter);
        $this->dataParser = Instance::ensure($this->dataParser);
        $this->file = Yii::getAlias($this->file);
        if (file_exists($this->file)) {
            $this->data = $this->dataParser->parseFile($this->file);
        }
    }

    /**
     * @param $data
     * @return mixed|void
     * @inheritdoc
     */
    public function set($key, $data)
    {
        ArrayHelper::setValue($this->data, $key, $data);
        $this->dataExporter->exportToFile($this->file, $this->data);
    }

    /**
     * @param $key
     * @return mixed|void
     * @inheritdoc
     */
    public function remove($key)
    {
        $this->set($key, null);
    }
}
