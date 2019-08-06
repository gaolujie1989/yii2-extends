<?php

namespace lujie\upload\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[UploadedFile]].
 *
 * @method UploadSavedFileQuery modelType($modelType)
 * @method UploadSavedFileQuery modelId($modelId)
 * @method UploadSavedFileQuery file($file)
 * @method UploadSavedFileQuery ext($ext)
 * @method UploadSavedFileQuery nameLike($name)
 *
 * @see UploadSavedFile
 */
class UploadSavedFileQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'modelType' => ['model_type'],
                    'modelId' => ['model_id'],
                    'file' => ['file'],
                    'ext' => ['ext'],
                    'nameLike' => ['name' => 'LIKE'],
                ]
            ]
        ]);
    }
}
