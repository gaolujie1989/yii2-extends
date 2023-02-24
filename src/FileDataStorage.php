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
    public function init(): void
    {
        parent::init();
        $this->fileReader = Instance::ensure($this->fileReader, FileReaderInterface::class);
        $this->fileWriter = Instance::ensure($this->fileWriter, FileWriterInterface::class);
        $this->file = Yii::getAlias($this->file);
    }

    /**
     * @param int|mixed|string $key
     * @return array|mixed|null
     * @inheritdoc
     */
    public function get($key)
    {
        $this->data = $this->fileReader->read($this->file);
        return parent::get($key);
    }

    /**
     * @return array|null
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function all(): ?array
    {
        $this->data = $this->fileReader->read($this->file);
        return parent::all();
    }

    /**
     * @param int|string $key
     * @param mixed $value
     * @return mixed|void
     * @inheritdoc
     */
    public function set($key, $value)
    {
        $this->data = $this->fileReader->read($this->file);
        ArrayHelper::setValue($this->data, $key, $value);
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
