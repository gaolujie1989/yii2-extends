<?php

namespace lujie\ar\history\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%history}}".
 *
 * @property int $history_id
 * @property string $model_type
 * @property int $model_id
 * @property int $parent_id
 * @property string $summary
 * @property array|null $details
 */
class History extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['model_type', 'summary'], 'default', 'value' => ''],
            [['model_id', 'parent_id'], 'default', 'value' => 0],
            [['details'], 'default', 'value' => []],
            [['model_id', 'parent_id'], 'integer'],
            [['details'], 'safe'],
            [['model_type'], 'string', 'max' => 50],
            [['summary'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'history_id' => Yii::t('lujie/history', 'History ID'),
            'model_type' => Yii::t('lujie/history', 'Model Type'),
            'model_id' => Yii::t('lujie/history', 'Model ID'),
            'parent_id' => Yii::t('lujie/history', 'Parent ID'),
            'summary' => Yii::t('lujie/history', 'Summary'),
            'details' => Yii::t('lujie/history', 'Details'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return HistoryQuery the active query used by this AR class.
     */
    public static function find(): HistoryQuery
    {
        return new HistoryQuery(static::class);
    }
}
