<?php

namespace lujie\upload\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use lujie\upload\behaviors\FileBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii2tech\ar\position\PositionBehavior;

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
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

    public const MODEL_TYPE = 'DEFAULT';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        $this->model_type = static::MODEL_TYPE;
        parent::init();
    }

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
            [['name', 'file'], 'string', 'max' => 255],
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
                'position' => [
                    'class' => PositionBehavior::class,
                    'groupAttributes' => ['model_type', 'model_id'],
                ],
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
        return (new UploadSavedFileQuery(static::class))->modelType(static::MODEL_TYPE);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'url',
        ]);
    }
}
