<?php

namespace lujie\upload\modes;

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
 * @method string getFileUrl()
 * @method string getFilePath()
 * @method string getFileContent()
 */
class UploadedFile extends ActiveRecord
{
    use TraceableBehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%uploaded_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
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
     * @return UploadedFileQuery|ActiveQuery
     * @inheritdoc
     */
    public static function find()
    {
        return new UploadedFileQuery(get_called_class());
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors()
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
