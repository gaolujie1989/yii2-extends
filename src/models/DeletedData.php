<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\backup\delete\models;

/**
 * This is the model class for table "{{%deleted_data}}".
 *
 * @property int $id
 * @property string $table_name
 * @property int $row_id
 * @property array|null $row_data
 *
 * @package lujie\backupdelete\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DeletedData extends \lujie\extend\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%deleted_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['table_name', 'row_id', 'row_data'], 'required'],
            [['row_data'], 'default', 'value' => []],
            [['row_id'], 'integer'],
            [['row_data'], 'safe'],
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
            'row_data' => 'Row Data',
        ];
    }

    /**
     * @return DeletedDataQuery
     * @inheritdoc
     */
    public static function find(): DeletedDataQuery
    {
        return new DeletedDataQuery(static::class);
    }
}
