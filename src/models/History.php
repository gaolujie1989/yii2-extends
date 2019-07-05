<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\history\models;


use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%history}}".
 *
 * @property integer $id
 * @property string $table_name
 * @property integer $row_id
 * @property array $old_data
 * @property array $new_data
 *
 * @package lujie\core\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class History extends ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait;

    /**
     * @return string
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%history}}';
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['table_name', 'row_id'], 'required'],
            [['row_id'], 'integer'],
            [['table_name'], 'string', 'max' => 50],
            [['old_data', 'new_data'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'table_name' => 'Table Name',
            'row_id' => 'Row ID',
            'old_data' => 'Old Data',
            'new_data' => 'New Data',
        ];
    }

    /**
     * @return HistoryQuery
     * @inheritdoc
     */
    public static function find(): HistoryQuery
    {
        return new HistoryQuery(static::class);
    }
}
