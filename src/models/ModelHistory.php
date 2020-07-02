<?php

namespace lujie\ar\history\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use lujie\scheduling\tests\unit\SchedulerTest;
use Yii;

/**
 * This is the model class for table "{{%history}}".
 *
 * @property int $model_history_id
 * @property string $model_type
 * @property int $model_id
 * @property int $parent_id
 * @property string $summary
 * @property array|null $details
 */
class ModelHistory extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    public const MODEL_TYPE = 'DEFAULT';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_history}}';
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
            [['summary'], 'default', 'value' => ''],
            [['model_id', 'parent_id'], 'default', 'value' => 0],
            [['details'], 'default', 'value' => []],
            [['model_id', 'parent_id'], 'integer'],
            [['details'], 'safe'],
            [['summary'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'model_history_id' => Yii::t('lujie/history', 'Model History ID'),
            'model_type' => Yii::t('lujie/history', 'Model Type'),
            'model_id' => Yii::t('lujie/history', 'Model ID'),
            'parent_id' => Yii::t('lujie/history', 'Parent ID'),
            'summary' => Yii::t('lujie/history', 'Summary'),
            'details' => Yii::t('lujie/history', 'Details'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ModelHistoryQuery the active query used by this AR class.
     */
    public static function find(): ModelHistoryQuery
    {
        return (new ModelHistoryQuery(static::class))->modelType(static::MODEL_TYPE);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        $fields = parent::fields();
        unset($fields['details']);
        return array_merge($fields, [
            'detailSummaries' => 'detailSummaries'
        ]);
    }

    /**
     * @return array
     */
    public function getDetailSummaries(): array
    {
        $summaries = [[], []];
        foreach ($this->details as $attribute => $detail) {
            $summaries[] = $this->getDetailSummary($attribute, $detail);
        }
        return array_merge(...$summaries);
    }

    /**
     * @param string $attribute
     * @param array $detail
     * @return array
     * @inheritdoc
     */
    protected function getDetailSummary(string $attribute, array $detail): array
    {
        $summaries = [];
        $diffValue = $detail;
        //for model array attribute
        foreach (['added', 'deleted'] as $operation) {
            if (isset($diffValue[$operation])) {
                $summaries[] = [
                    'attribute' => $attribute,
                    'operation' => $operation,
                    'detail' => implode(',', $diffValue[$operation]),
                ];
            }
        }
        if (isset($diffValue['modified'])) {
            //for model base attribute
            if (is_string($diffValue['modified'])) {
                $summaries[] = [
                    'attribute' => $attribute,
                    'operation' => 'modified',
                    'detail' => $diffValue['modified'],
                ];
            } else {
                //for model one-one relation base attribute
                foreach ($diffValue['modified'] as $attr => $modified) {
                    $summaries[] = [
                        'attribute' => $attribute . '.' . $attr,
                        'operation' => 'modified',
                        'detail' => $modified,
                    ];
                }
            }
        }
        return $summaries;
    }
}
