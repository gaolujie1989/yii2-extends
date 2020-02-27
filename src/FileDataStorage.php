<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\storage;

use lujie\data\loader\ArrayDataLoader;
use lujie\extend\file\FileReaderInterface;
use lujie\extend\file\FileWriterInterface;
use lujie\extend\file\readers\PhpReader;
use lujie\extend\file\writers\PhpWriter;
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
     * @var FileReaderInterface
     */
    public $fileReader = PhpReader::class;

    /**
     * @var FileWriterInterface
     */
    public $fileWriter = PhpWriter::class;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->fileReader = Instance::ensure($this->fileReader, FileReaderInterface::class);
        $this->fileWriter = Instance::ensure($this->fileWriter, FileWriterInterface::class);
        $this->file = Yii::getAlias($this->file);
        if (file_exists($this->file)) {
            $this->data = $this->fileWriter->parseFile($this->file);
        }
    }

    /**
     * @param int|string $key
     * @param mixed $data
     * @return mixed|void
     * @inheritdoc
     */
    public function set($key, $data)
    {
        ArrayHelper::setValue($this->data, $key, $data);
        $this->fileWriter->write($this->file, $this->data);
    }

    /**
     * @param int|string $key
     * @return mixed|void
     * @inheritdoc
     */
    public function remove($key)
    {
        $this->set($key, null);
    }
}
