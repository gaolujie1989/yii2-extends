<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\backup\delete\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%deleted_data}}".
 *
 * @property integer $id
 * @property string $table_name
 * @property integer $row_id
 * @property array $row_data
 *
 * @package lujie\backupdelete\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DeletedData extends ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait;

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
            [['row_id'], 'integer'],
            [['table_name'], 'string', 'max' => 50],
            [['row_data'], 'safe']
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
