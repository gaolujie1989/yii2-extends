<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\forms;

use creocoder\flysystem\Filesystem;
use lujie\data\exchange\FileImporter;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FillDefaultValueTransformer;
use lujie\executing\Executor;
use lujie\extend\base\ModelAttributeTrait;
use lujie\upload\behaviors\FileTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class OwnerProductImportForm
 *
 * @property array $files
 *
 * @package ccship\common\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileImportForm extends Model
{
    use FileTrait, ModelAttributeTrait;

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
    public $dataAttributes = [];

    /**
     * @var ?Filesystem
     */
    public $fs;

    /**
     * @var string
     */
    public $path = '@statics/uploads';

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
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return array_merge([$this->fileAttribute], $this->dataAttributes);
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
        $rules = [
            [[$this->fileAttribute], 'formatFiles'],
            [[$this->fileAttribute], 'required'],
            [[$this->fileAttribute], 'validateFilesExist'],
        ];
        if ($this->dataAttributes) {
            $rules[] = [$this->dataAttributes, 'required'];
        }
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function formatFiles(): void
    {
        $files = $this->files;
        if ($files && is_array($files) && is_array(reset($files))) {
            $this->files = array_filter(ArrayHelper::getColumn($files, 'file'));
        } else if ($files && !is_array($files)) {
            $this->files = [$files];
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
        return array_merge([$this->fileAttribute, 'affectedRowCounts'], $this->dataAttributes);
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

        $this->applyDataAttributeValues();
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

    /**
     * @inheritdoc
     */
    protected function applyDataAttributeValues(): void
    {
        if ($this->dataAttributes) {
            $dataAttributeValues = $this->getAttributes($this->dataAttributes);
            $fillOwnerIdTransformer = new FillDefaultValueTransformer(['defaultValues' => $dataAttributeValues]);
            $transformer = $this->fileImporter->transformer;
            if ($transformer instanceof ChainedTransformer) {
                array_unshift($transformer->transformers, $fillOwnerIdTransformer);
            } else {
                $this->fileImporter->transformer = new ChainedTransformer([
                    'transformers' => array_filter([$fillOwnerIdTransformer, $transformer])
                ]);
            }
        }
    }
}
