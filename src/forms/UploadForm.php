<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\upload\forms;

use creocoder\flysystem\Filesystem;
use lujie\upload\behaviors\UploadBehavior;
use yii\base\Model;
use yii\base\ModelEvent;
use yii\web\UploadedFile;

/**
 * Class UploadForm
 * @package lujie\uploadImport\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadForm extends Model
{
    /**
     * @var string|UploadedFile
     */
    public $file;

    /**
     * @var string
     */
    public $inputName = 'file';

    /**
     * @var array
     */
    public $allowedExtensions = ['csv', 'xls', 'xlsx'];

    /**
     * @var bool
     */
    public $checkExtensionByMimeType = false;

    /**
     * @var string
     */
    public $path = '@statics/uploads';

    /**
     * @var ?Filesystem
     */
    public $fs;

    /**
     * @var string
     */
    public $fileNameTemplate = '{baseName}_{datetime}_{rand}.{ext}';

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
                'inputName' => $this->inputName,
                'path' => $this->path,
                'fs' => $this->fs,
                'fileNameTemplate' => $this->fileNameTemplate,
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

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return ['file'];
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function saveUploadedFile(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var UploadBehavior $behavior */
        $behavior = $this->getBehavior('upload');
        //call behavior save uploaded file
        $behavior->beforeSave(new ModelEvent());
        return true;
    }
}
