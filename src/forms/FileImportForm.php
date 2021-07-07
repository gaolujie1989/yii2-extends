<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\forms;

use creocoder\flysystem\Filesystem;
use lujie\data\exchange\FileImporter;
use lujie\executing\Executor;
use lujie\upload\behaviors\FileTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class OwnerProductImportForm
 * @package ccship\common\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileImportForm extends Model
{
    use FileTrait;

    /**
     * @var string
     */
    public $fileAttribute = 'files';

    /**
     * @var string
     */
    public $fileAttributeLabel = 'Files';

    /**
     * @var array
     */
    public $files;

    /**
     * @var ?Filesystem
     */
    public $fs;

    /**
     * @var string
     */
    public $path = '/tmp/imports';

    /**
     * @var FileImporter
     */
    public $fileImporter;

    /**
     * @var Executor|string|null
     */
    public $executor = 'executor';

    /**
     * @var array import results
     */
    public $affectedRowCounts;

    /**
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->initFsAndPath();
        $this->fileImporter = Instance::ensure($this->fileImporter, FileImporter::class);
        if ($this->executor && Yii::$app->has($this->executor)) {
            $this->executor = Instance::ensure($this->executor, Executor::class);
        } else {
            $this->executor = null;
        }
    }

    #region model overwrites

    /**
     * @param string $name
     * @return array|mixed
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($name === $this->fileAttribute) {
            return $this->files;
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if ($name === $this->fileAttribute) {
            $this->files = is_array($value) ? $value : [$value];
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return [$this->fileAttribute];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            $this->fileAttribute => $this->fileAttributeLabel
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [[$this->fileAttribute], 'formatFiles'],
            [[$this->fileAttribute], 'required'],
            [[$this->fileAttribute], 'validateFilesExist'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function formatFiles(): void
    {
        if ($this->files && is_array($this->files) && is_array(reset($this->files))) {
            $this->files = array_filter(ArrayHelper::getColumn($this->files, 'file'));
        }
    }

    /**
     * @inheritdoc
     */
    public function validateFilesExist(): void
    {
        foreach ($this->files as $file) {
            if (!$this->existFile($file)) {
                $this->addError($this->fileAttribute, Yii::t('lujie/import', 'Import file not exists.'));
            }
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return [$this->fileAttribute, 'affectedRowCounts'];
    }

    #endregion

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function import(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $fileImporter = $this->fileImporter;
        foreach ($this->files as $file) {
            $filePath = $this->path . $file;
            $fileImporter->prepare($filePath, $this->fs);
            $executed = $this->executor
                ? $this->executor->execute($fileImporter)
                : $fileImporter->execute();
            if ($fileImporter->getErrors()) {
                $this->addError($this->fileAttribute, "File {$file} Error:" . Json::encode($fileImporter->getErrors()));
            } elseif ($executed === false) {
                $this->addError($this->fileAttribute, "File {$file} Unknown Error");
            } else {
                $this->affectedRowCounts[$file] = $fileImporter->getAffectedRowCounts();
            }
        }
        return !$this->hasErrors();
    }
}
