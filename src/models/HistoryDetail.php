<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\arhistory\models;


use lujie\core\db\ActiveRecord;

/**
 * This is the model class for table "{{%history_detail}}".
 *
 * @property integer $id
 * @property integer $history_id
 * @property string $field_name
 * @property string $old_value
 * @property string $new_value
 */
class HistoryDetail extends ActiveRecord
{
    const HISTORY_CLASS = History::class;

    public $tableName;
    public $rowId;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_history_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            static::SCENARIO_DEFAULT => [
                [['old_value', 'new_value'], 'default', 'value' => ''],
                [['history_id', 'field_name'], 'required'],
                [['history_id'], 'integer'],
                [['field_name'], 'string', 'max' => 50],
            ],
            static::SCENARIO_SEARCH => [
                [['history_id', 'field_name'], 'safe'],
                [['tableName', 'rowId'], 'safe'],
            ],
        ][$this->getScenario()];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'history_id' => 'History ID',
            'field_name' => 'Field Name',
            'old_value' => 'Old Value',
            'new_value' => 'New Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistory()
    {
        return $this->hasMany(static::HISTORY_CLASS, ['id' => 'history_id']);
    }

    /**
     * @param \yii\db\ActiveQuery $query
     * @inheritdoc
     */
    public function searchQuery($query)
    {
        $query->andFilterWhere([
            'history_id' => $this->history_id,
            'field_name' => $this->field_name,
        ]);
        if ($this->tableName || $this->rowId) {
            $query->innerJoinWith(['history'])
                ->andFilterWhere([
                    'table_name' => $this->tableName,
                    'row_id' => $this->rowId,
                ]);
        }
    }

    /**
     * @param ActiveRecord $model
     * @param null $limit
     * @inheritdoc
     */
    public static function searchHistoryVersions(ActiveRecord $model, $fieldName)
    {
        /** @var \yii\db\ActiveQuery $query */
        $query = static::find()->alias('d')->innerJoinWith(['history h'])
            ->andWhere([
                'h.table_name' => $model::tableName(),
                'h.row_id' => $model->getId(),
                'd.field_name' => $fieldName,
            ])->orderBy(['h.created_at' => SORT_DESC]);
        return $query;
    }

    /**
     * @param ActiveRecord|null $model
     * @return \yii\db\ActiveQuery
     * @inheritdoc
     */
    public static function searchLastVersion(ActiveRecord $model = null)
    {
        $lastCreatedAtQuery = static::find()->innerJoinWith(['history'])
            ->select(['table_name', 'row_id', 'field_name', 'MAX(created_at) AS max_created_at'])
            ->groupBy(['table_name', 'row_id', 'field_name']);
        if ($model) {
            $lastCreatedAtQuery->andWhere([
                'table_name' => $model::tableName(),
                'row_id' => $model->getId(),
            ]);
        }

        /** @var \yii\db\ActiveQuery $query */
        $query = static::find()->alias('d')->innerJoinWith(['history h'])
            ->select(['h.table_name', 'h.row_id', 'd.field_name', 'd.old_value', 'h.created_at', 'h.created_by'])
            ->leftJoin(['t' => $lastCreatedAtQuery], [
                't.table_name' => 'h.table_name',
                't.row_id' => 'h.row_id',
                't.field_name' => 'd.field_name',
                't.max_created_at' => 'd.created_at',
            ]);
        if ($model) {
            $query->andWhere([
                'h.table_name' => $model::tableName(),
                'h.row_id' => $model->getId(),
            ]);
        }

        return $query;
    }
}