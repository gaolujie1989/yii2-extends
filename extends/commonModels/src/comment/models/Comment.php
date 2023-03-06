<?php

namespace lujie\common\comment\models;

use Yii;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property int $comment_id
 * @property string $model_type
 * @property int $model_id
 * @property string $content
 */
class Comment extends \lujie\extend\db\ActiveRecord
{
    public const MODEL_TYPE = 'DEFAULT';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%comment}}';
    }

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
    public function rules(): array
    {
        return [
            [['content'], 'default', 'value' => ''],
            [['model_id'], 'default', 'value' => 0],
            [['model_id'], 'integer'],
            [['content'], 'string', 'max' => 2000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'comment_id' => Yii::t('lujie/common', 'Comment ID'),
            'model_type' => Yii::t('lujie/common', 'Model Type'),
            'model_id' => Yii::t('lujie/common', 'Model ID'),
            'content' => Yii::t('lujie/common', 'Content'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CommentQuery the active query used by this AR class.
     */
    public static function find(): CommentQuery
    {
        return (new CommentQuery(static::class))->andFilterWhere(['model_type' => static::MODEL_TYPE]);
    }
}
