<?php

namespace lujie\ar\deleted\backup\models;

use Yii;

/**
 * This is the model class for table "{{%deleted_data}}".
 *
 * @property int $id
 * @property string $table_name
 * @property int $row_id
 * @property string $row_key
 * @property int $row_parent_id
 * @property array|null $row_data
 */
class DeletedData extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%deleted_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['table_name', 'row_key'], 'default', 'value' => ''],
            [['row_id', 'row_parent_id'], 'default', 'value' => 0],
            [['row_data'], 'default', 'value' => []],
            [['row_id', 'row_parent_id'], 'integer'],
            [['row_data'], 'safe'],
            [['table_name', 'row_key'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('lujie/common', 'ID'),
            'table_name' => Yii::t('lujie/common', 'Table Name'),
            'row_id' => Yii::t('lujie/common', 'Row ID'),
            'row_key' => Yii::t('lujie/common', 'Row Key'),
            'row_parent_id' => Yii::t('lujie/common', 'Row Parent ID'),
            'row_data' => Yii::t('lujie/common', 'Row Data'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DeletedDataQuery the active query used by this AR class.
     */
    public static function find(): DeletedDataQuery
    {
        return new DeletedDataQuery(static::class);
    }
}
