<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\upload;

use lujie\upload\modes\UploadedFile;
use yii\base\InvalidConfigException;

/**
 * Class UploadForm
 * @package lujie\uploadImport\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadedFileForm extends UploadedFile
{
    /**
     * not use /tmp, file may be clean by system
     * @var string
     */
    public $path = '@uploads';

    public $inputName = 'file';

    public $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    public $checkExtensionByMimeType = false;

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
    public function behaviors()
    {
        $modelType = strtolower($this->model_type);
        return array_merge(parent::behaviors(), [
            'upload' => [
                'class' => UploadBehavior::class,
                'attribute' => 'file',
                'inputName' => $this->inputName,
                'path' => $this->path,
                'fs' => 'filesystem',
                'newNameTemplate' => "{$modelType}/{date}/{$modelType}_{datetime}_{rand}.{ext}"
            ],
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'required'],
            [['file'], 'file',
                'extensions' => $this->allowedExtensions,
                'checkExtensionByMimeType' => $this->checkExtensionByMimeType
            ],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields()
    {
        return ['file'];
    }
}
