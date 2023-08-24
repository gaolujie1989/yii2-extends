<?php

namespace lujie\common\deleted\backup\models;

use Yii;

/**
 * This is the model class for table "{{%deleted_backup}}".
 *
 * @property int $deleted_backup_id
 * @property string $model_type
 * @property string $model_class
 * @property int $row_id
 * @property string $row_key
 * @property int $row_parent_id
 * @property array|null $row_data
 *
 * @method array|DeletedBackup|null findOne($condition)
 * @method array|DeletedBackup[] findAll($condition)
 */
class DeletedBackup extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%deleted_backup}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['model_type', 'model_class', 'row_key'], 'default', 'value' => ''],
            [['row_id', 'row_parent_id'], 'default', 'value' => 0],
            [['row_data'], 'default', 'value' => []],
            [['row_id', 'row_parent_id'], 'integer'],
            [['row_data'], 'safe'],
            [['model_type', 'row_key'], 'string', 'max' => 50],
            [['model_class'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'deleted_backup_id' => Yii::t('lujie/common', 'Deleted Backup ID'),
            'model_type' => Yii::t('lujie/common', 'Model Type'),
            'model_class' => Yii::t('lujie/common', 'Model Class'),
            'row_id' => Yii::t('lujie/common', 'Row ID'),
            'row_key' => Yii::t('lujie/common', 'Row Key'),
            'row_parent_id' => Yii::t('lujie/common', 'Row Parent ID'),
            'row_data' => Yii::t('lujie/common', 'Row Data'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DeletedBackupQuery the active query used by this AR class.
     */
    public static function find(): DeletedBackupQuery
    {
        return new DeletedBackupQuery(static::class);
    }
}
