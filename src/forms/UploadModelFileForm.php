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
    public $path = '@uploads';

    /**
     * @var string
     */
    public $fs = 'filesystem';

    /**
     * @var string
     */
    public $fileNameTemplate = '';

    /**
     * @var array
     */
    public $allowedModelTypes = [];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        if (empty($this->fileNameTemplate)) {
            $modelType = strtolower($this->model_type);
            $this->fileNameTemplate = "{$modelType}/{datetime}_{rand}.{ext}";
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
                'fileNameTemplate' => $this->fileNameTemplate
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
}
