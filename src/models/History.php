<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\history\models;


use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%history}}".
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property int $parent_id
 * @property array|null $summary
 * @property array|null $details
 *
 * @package lujie\core\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class History extends ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

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
            [['old_data', 'new_data'], 'default', 'value' => []],
            [['row_id', 'created_at', 'created_by'], 'integer'],
            [['old_data', 'new_data'], 'safe'],
            [['table_name'], 'string', 'max' => 50],
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
