<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\arhistory\models;


/**
 * This is the model class for table "{{%history}}".
 *
 * @property integer $id
 * @property integer $event
 * @property string $table_name
 * @property integer $row_id
 * @property integer $custom_id
 * @property array $custom_data
 * @property integer $created_at
 * @property integer $created_by
 *
 * @package lujie\core\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class History extends \lujie\core\db\ActiveRecord
{
    const DETAIL_CLASS = HistoryDetail::class;

    public $messageTemplate = '{username} change attributes: {dirtyAttributes}';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            static::SCENARIO_DEFAULT => [
                [['custom_id'], 'default', 'value' => 0],
                [['event', 'table_name', 'row_id'], 'required'],
                [['event', 'row_id'], 'integer'],
                [['table_name'], 'string', 'max' => 50],
                [['custom_data'], 'safe']
            ],
            static::SCENARIO_SEARCH => [
                [['custom_id', 'row_id'], 'safe'],
            ]
        ][$this->getScenario()];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event' => 'Event',
            'table_name' => 'Table Name',
            'row_id' => 'Row ID',
            'custom_id' => 'Custom ID',
            'custom_data' => 'Custom Data',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetails()
    {
        return $this->hasMany(static::DETAIL_CLASS, ['history_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return array_merge(parent::extraFields(), [
            'details'
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery|\yii\db\QueryInterface
     * @inheritdoc
     */
    public function query()
    {
        return static::find()->andFilterWhere([
            'table_name' => $this->table_name,
            'custom_id' => $this->custom_id,
            'row_id' => $this->row_id,
        ]);
    }
}