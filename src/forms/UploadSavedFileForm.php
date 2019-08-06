<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\upload\forms;

use lujie\upload\behaviors\UploadBehavior;
use lujie\upload\models\UploadSavedFile;
use yii\base\InvalidConfigException;

/**
 * Class UploadForm
 * @package lujie\uploadImport\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadSavedFileForm extends UploadSavedFile
{
    public $inputName = 'file';

    public $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    public $checkExtensionByMimeType = false;

    /**
     * @var array
     */
    public $modelTypePathPrefixes = [];

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        if (!$this->model_type) {
            throw new InvalidConfigException('Uploaded file model type must be set.');
        }
        parent::init();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $modelType = strtolower($this->model_type);
        $pathPrefix = $this->modelTypePathPrefixes[$this->model_type] ?? $modelType;
        return array_merge(parent::behaviors(), [
            'upload' => [
                'class' => UploadBehavior::class,
                'attribute' => 'file',
                'inputName' => $this->inputName,
                'fs' => 'filesystem',
                'newNameTemplate' => "{$pathPrefix}/{date}/{$modelType}_{datetime}_{rand}.{ext}"
            ],
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['file'], 'required'],
            [['file'], 'file',
                'extensions' => $this->allowedExtensions,
                'checkExtensionByMimeType' => $this->checkExtensionByMimeType
            ],
        ];
    }
}
