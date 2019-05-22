<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\import;

use lujie\data\exchange\FileImporter;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\di\Instance;

/**
 * Class OwnerProductImportForm
 * @package ccship\common\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileImportForm extends Model
{
    /**
     * @var string
     */
    public $fileAttribute = 'file';

    /**
     * @var string
     */
    public $fileAttributeLabel = 'File';

    /**
     * @var array
     */
    public $files;

    /**
     * @var string
     */
    public $path = '/tmp/';

    /**
     * @var FileImporter
     */
    public $fileImporter;

    /**
     * @var array import results
     */
    public $affectedRowCounts;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->path = Yii::getAlias($this->path);
        $this->path = rtrim($this->path, '/') . '/';
        $this->fileImporter = Instance::ensure($this->fileImporter, FileImporter::class);
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
            [[$this->fileAttribute], 'required'],
            [[$this->fileAttribute], 'validateFilesExist'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function validateFilesExist(): void
    {
        foreach ($this->files as $file) {
            $filePath = $this->path . $file;
            if (!file_exists($filePath)) {
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
     * @inheritdoc
     */
    public function import(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $importer = $this->fileImporter;
        foreach ($this->files as $file) {
            if ($importer->importFromFile($file)) {
                $this->affectedRowCounts[$file] = $importer->getAffectedRowCounts();
            } else {
                $this->addError($this->fileAttribute, [$file => $importer->getErrors()]);
            }
        }
        return !$this->hasErrors();
    }
}
