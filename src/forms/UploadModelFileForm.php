<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\upload\forms;

use lujie\upload\behaviors\UploadBehavior;
use lujie\upload\models\UploadModelFile;
use yii\base\InvalidConfigException;

/**
 * Class UploadForm
 * @package lujie\uploadImport\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadModelFileForm extends UploadModelFile
{
    /**
     * @var string
     */
    public $inputName = 'file';

    /**
     * @var array allowedExtensions like ['jpg', 'jpeg', 'png', 'gif']
     */
    public $allowedExtensions = [];

    /**
     * @var bool
     */
    public $checkExtensionByMimeType = false;

    /**
     * @var string
     */
    public $path = '@uploads';

    /**
     * @var string
     */
    public $fs = 'filesystem';

    /**
     * @var string
     */
    public $filePathTemplate = '{model_type}/{datetime}_{rand}.{ext}';

    /**
     * @var array
     */
    public $allowedModelTypes = [];

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        if ($this->model_type) {
            $this->allowedModelTypes[] = $this->model_type;
        } else if ($this->allowedModelTypes) {
            $this->model_type = reset($this->allowedModelTypes);
        } else {
            throw new InvalidConfigException('The property `allowedModelTypes` must be set.');
        }
        parent::init();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'upload' => [
                'class' => UploadBehavior::class,
                'attribute' => 'file',
                'nameAttribute' => 'name',
                'sizeAttribute' => 'size',
                'extAttribute' => 'ext',
                'inputName' => $this->inputName,
                'path' => $this->path,
                'fs' => $this->fs,
                'fileNameTemplate' => $this->filePathTemplate
            ],
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge([
            [['position'], 'default', 'value' => 99],
            [['file', 'model_id'], 'required'],
            [['model_id', 'model_parent_id'], 'integer'],
            [['file'], 'file',
                'extensions' => $this->allowedExtensions,
                'checkExtensionByMimeType' => $this->checkExtensionByMimeType
            ],
        ], $this->allowedModelTypes ? [
            [['model_type'], 'in', 'range' => $this->allowedModelTypes],
        ] : []);
    }

    /**
     * @param bool $insert
     * @return bool
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        if (empty($this->model_type)) {
            $this->model_type = reset($this->allowedModelTypes);
        }
        return parent::beforeSave($insert);
    }
}
