<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\upload\forms;

use lujie\upload\behaviors\UploadBehavior;
use lujie\upload\models\UploadModelFile;
use yii\helpers\Inflector;

/**
 * Class UploadForm
 * @package lujie\uploadImport\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadModelFileForm extends UploadModelFile
{
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
     * @var array
     */
    public $modelTypePathPrefixes = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $modelType = strtolower($this->model_type);
        $pathPrefix = $this->modelTypePathPrefixes[$this->model_type] ?? $modelType;
        $pathPrefix = lcfirst(Inflector::pluralize(Inflector::camelize($pathPrefix)));
        return array_merge(parent::behaviors(), [
            'upload' => [
                'class' => UploadBehavior::class,
                'attribute' => 'file',
                'nameAttribute' => 'name',
                'sizeAttribute' => 'size',
                'extAttribute' => 'ext',
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
            [['file', 'model_id'], 'required'],
            [['model_id', 'model_parent_id'], 'integer'],
            [['file'], 'file',
                'extensions' => $this->allowedExtensions,
                'checkExtensionByMimeType' => $this->checkExtensionByMimeType
            ],
        ];
    }
}
