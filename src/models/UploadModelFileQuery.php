<?php

namespace lujie\upload\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[UploadedFile]].
 *
 * @method UploadModelFileQuery id($id)
 * @method UploadModelFileQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method UploadModelFileQuery uploadModelFileId($uploadModelFileId)
 * @method UploadModelFileQuery modelType($modelType)
 * @method UploadModelFileQuery modelId($modelId)
 * @method UploadModelFileQuery modelParentId($modelParentId)
 * @method UploadModelFileQuery file($file)
 * @method UploadModelFileQuery ext($ext)
 * @method UploadModelFileQuery nameLike($name)
 *
 * @method array|UploadModelFile[] all($db = null)
 * @method array|UploadModelFile|null one($db = null)
 * @method array|UploadModelFile[] each($batchSize = 100, $db = null)
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
                    'uploadModelFileId' => 'upload_model_file_id',
                    'modelType' => 'model_type',
                    'modelId' => 'model_id',
                    'modelParentId' => 'model_parent_id',
                    'file' => 'file',
                    'ext' => ['ext'],
                    'nameLike' => ['name' => 'LIKE'],
                ]
            ]
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function clearWhereAppendOnCondition(): ActiveQuery
    {
        /** @var UploadModelFile $modelClass */
        $modelClass = $this->modelClass;
        return $this->where([])->andOnCondition(['model_type' => $modelClass::MODEL_TYPE]);
    }
}
