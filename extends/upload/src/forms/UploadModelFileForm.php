<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\upload\forms;

use lujie\upload\behaviors\UploadBehavior;
use lujie\upload\models\UploadModelFile;

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
    public $path = '@statics';

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
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->model_type) {
            $this->allowedModelTypes[] = $this->model_type;
        } elseif ($this->allowedModelTypes) {
            $this->model_type = reset($this->allowedModelTypes);
        }
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
        $rules = [
            [['file', 'model_id'], 'required'],
            [['model_id', 'model_parent_id'], 'default', 'value' => 0],
            [['position'], 'default', 'value' => 99],
            [['model_id', 'model_parent_id', 'position'], 'integer'],
            [['file'], 'file',
                'extensions' => $this->allowedExtensions,
                'checkExtensionByMimeType' => $this->checkExtensionByMimeType
            ],
        ];

        if (empty($this->allowedModelTypes)) {
            $rules[] = [['model_type'], 'string', 'max' => 50];
        } else if (count($this->allowedModelTypes) > 1) {
            $rules[] = [['model_type'], 'in', 'range' => $this->allowedModelTypes];
        }
        return $rules;
    }

    /**
     * @param bool $insert
     * @return bool
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        if (empty($this->model_type)) {
            $this->model_type = $this->allowedModelTypes ? reset($this->allowedModelTypes) : 'UNKNOWN';
        }
        return parent::beforeSave($insert);
    }
}
