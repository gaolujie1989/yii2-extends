<?php

namespace lujie\extend\log\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $level
 * @property string $category
 * @property float $log_at
 * @property float $duration
 * @property string|null $prefix
 * @property string|null $message
 * @property string|null $summary
 * @property int $memory_usage
 * @property int $memory_diff
 *
 * @method array|Log|null findOne($condition)
 * @method array|Log[] findAll($condition)
 */
class Log extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['level', 'log_at', 'duration', 'memory_usage', 'memory_diff'], 'default', 'value' => 0],
            [['category', 'message'], 'default', 'value' => ''],
            [['level', 'memory_usage', 'memory_diff'], 'integer'],
            [['log_at', 'duration'], 'number'],
            [['message'], 'string'],
            [['category', 'prefix'], 'string', 'max' => 100],
            [['summary'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('lujie/extend', 'ID'),
            'level' => Yii::t('lujie/extend', 'Level'),
            'category' => Yii::t('lujie/extend', 'Category'),
            'log_at' => Yii::t('lujie/extend', 'Log At'),
            'duration' => Yii::t('lujie/extend', 'Duration'),
            'prefix' => Yii::t('lujie/extend', 'Prefix'),
            'message' => Yii::t('lujie/extend', 'Message'),
            'summary' => Yii::t('lujie/extend', 'Summary'),
            'memory_usage' => Yii::t('lujie/extend', 'Memory Usage'),
            'memory_diff' => Yii::t('lujie/extend', 'Memory Diff'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return LogQuery the active query used by this AR class.
     */
    public static function find(): LogQuery
    {
        return new LogQuery(static::class);
    }
}
