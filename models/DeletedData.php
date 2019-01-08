<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\backupdelete\models;


/**
 * This is the model class for table "{{%deleted_data}}".
 *
 * @property integer $id
 * @property string $table_name
 * @property integer $row_id
 * @property array $row_data
 * @property integer $created_at
 * @property integer $created_by
 *
 * @package lujie\backupdelete\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DeletedData extends \lujie\core\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%deleted_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            static::SCENARIO_DEFAULT => [
                [['table_name', 'row_id', 'row_data'], 'required'],
                [['row_id'], 'integer'],
                [['table_name'], 'string', 'max' => 50],
                [['row_data'], 'safe']
            ],
            static::SCENARIO_SEARCH => [
                [['table_name', 'row_id'], 'safe'],
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
            'table_name' => 'Table Name',
            'row_id' => 'Row ID',
            'row_data' => 'Custom Data',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery|\yii\db\QueryInterface
     * @inheritdoc
     */
    public function query()
    {
        return static::find()->andFilterWhere([
            'table_name' => $this->table_name,
            'row_id' => $this->row_id,
        ]);
    }
}