<?php

namespace lujie\upload\models;

use lujie\extend\db\TraceableBehaviorTrait;
use lujie\upload\FileBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%uploaded_file}}".
 *
 * @property int $uploaded_file_id
 * @property string $model_type
 * @property int $model_id
 * @property int $position
 * @property string $file
 * @property string $name
 * @property string $ext
 * @property int $size
 *
 * @property string $url
 * @property string $path
 * @property string $content
 *
 * @method string getUrl()
 * @method string getPath()
 * @method string getContent()
 */
class UploadSavedFile extends ActiveRecord
{
    use TraceableBehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%upload_saved_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['file'], 'required'],
            [['model_id', 'position', 'size'], 'integer'],
            [['owner'], 'string', 'max' => 20],
            [['name', 'file'], 'string', 'max' => 255],
            [['ext'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'uploaded_file_id' => Yii::t('lujie/upload', 'Uploaded File ID'),
            'model_type' => Yii::t('lujie/upload', 'Model Type'),
            'model_id' => Yii::t('lujie/upload', 'Model ID'),
            'position' => Yii::t('lujie/upload', 'Position'),
            'file' => Yii::t('lujie/upload', 'File'),
            'name' => Yii::t('lujie/upload', 'Name'),
            'ext' => Yii::t('lujie/upload', 'Ext'),
            'size' => Yii::t('lujie/upload', 'Size'),
        ];
    }

    /**
     * @return UploadSavedFileQuery|ActiveQuery
     * @inheritdoc
     */
    public static function find(): UploadSavedFileQuery
    {
        return new UploadSavedFileQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            $this->traceableBehaviors(),
            [
                'file' => [
                    'class' => FileBehavior::class,
                    'attribute' => 'file',
                ]
            ]
        );
    }
}
