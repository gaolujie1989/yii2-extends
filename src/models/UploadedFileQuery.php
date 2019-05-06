<?php

namespace lujie\upload\modes;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[UploadedFile]].
 *
 * @method UploadedFileQuery modelType($modelType)
 * @method UploadedFileQuery modelId($modelId)
 *
 * @see UploadedFile
 */
class UploadedFileQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'modelType' => ['model_type'],
                    'modelId' => ['model_id'],
                ]
            ]
        ]);
    }
}
