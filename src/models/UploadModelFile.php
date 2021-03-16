<?php

namespace lujie\upload\models;

use lujie\extend\db\AliasFieldTrait;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use lujie\upload\behaviors\FileBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%uploaded_file}}".
 *
 * @property int $upload_model_file_id
 * @property string $model_type
 * @property int $model_id
 * @property int $model_parent_id
 * @property int $position
 * @property string $file
 * @property string $name
 * @property string $ext
 * @property int $size
 *
 * @property string $url
 * @property string $content
 *
 * @method string getUrl()
 * @method string getContent()
 */
class UploadModelFile extends ActiveRecord
{
    use TraceableBehaviorTrait, AliasFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    public const MODEL_TYPE = '';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        if (empty($this->model_type)) {
            $this->model_type = static::MODEL_TYPE;
        }
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%upload_model_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['file'], 'required'],
            [['model_type', 'name', 'ext'], 'default', 'value' => ''],
            [['model_id', 'model_parent_id', 'position', 'size'], 'default', 'value' => 0],
            [['model_id', 'model_parent_id', 'position', 'size'], 'integer'],
            [['model_type'], 'string', 'max' => 50],
            [['file', 'name'], 'string', 'max' => 255],
            [['ext'], 'string', 'max' => 10],
        ];
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'upload_model_file_id' => Yii::t('lujie/upload', 'Upload Model File ID'),
            'model_type' => Yii::t('lujie/upload', 'Model Type'),
            'model_id' => Yii::t('lujie/upload', 'Model ID'),
            'model_parent_id' => Yii::t('lujie/upload', 'Model Parent ID'),
            'position' => Yii::t('lujie/upload', 'Position'),
            'file' => Yii::t('lujie/upload', 'File'),
            'name' => Yii::t('lujie/upload', 'Name'),
            'ext' => Yii::t('lujie/upload', 'Ext'),
            'size' => Yii::t('lujie/upload', 'Size'),
        ];
    }

    /**
     * because upload model file need to be managed at same location, so if not set model type, query all
     * @return UploadModelFileQuery
     * @inheritdoc
     */
    public static function find(): UploadModelFileQuery
    {
        $query = new UploadModelFileQuery(static::class);
        return static::MODEL_TYPE ? $query->modelType(static::MODEL_TYPE) : $query;
    }
}
