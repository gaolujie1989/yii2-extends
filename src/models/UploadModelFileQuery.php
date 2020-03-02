<?php

namespace lujie\upload\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[UploadedFile]].
 *
 * @method UploadModelFileQuery id($id)
 * @method UploadModelFileQuery modelType($modelType)
 * @method UploadModelFileQuery modelId($modelId)
 * @method UploadModelFileQuery modelParentId($modelParentId)
 * @method UploadModelFileQuery file($file)
 * @method UploadModelFileQuery ext($ext)
 * @method UploadModelFileQuery nameLike($name)
 *
 * @see UploadModelFile
 */
class UploadModelFileQuery extends \yii\db\ActiveQuery
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
                    'modelParentId' => ['model_parent_id'],
                    'file' => ['file'],
                    'ext' => ['ext'],
                    'nameLike' => ['name' => 'LIKE'],
                ]
            ]
        ]);
    }
}
