<?php

namespace lujie\barcode\assigning\models;

use Yii;

/**
 * This is the model class for table "{{%barcode}}".
 *
 * @property int $barcode_id
 * @property string $code_type EAN or UPC
 * @property string $code_text
 * @property string $model_type
 * @property int $model_id
 * @property int $owner_id
 */
class Barcode extends \lujie\extend\db\ActiveRecord
{
    public const CODE_TYPE_EAN = 'EAN';
    public const CODE_TYPE_UPC = 'UPC';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%barcode}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['code_type', 'code_text', 'model_type'], 'default', 'value' => ''],
            [['model_id', 'owner_id'], 'default', 'value' => 0],
            [['model_id', 'owner_id'], 'integer'],
            [['code_type'], 'string', 'max' => 3],
            [['code_text'], 'string', 'max' => 13],
            [['model_type'], 'string', 'max' => 50],
            [['code_text'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'barcode_id' => Yii::t('lujie/barcode', 'Barcode ID'),
            'code_type' => Yii::t('lujie/barcode', 'Code Type'),
            'code_text' => Yii::t('lujie/barcode', 'Code Text'),
            'model_type' => Yii::t('lujie/barcode', 'Model Type'),
            'model_id' => Yii::t('lujie/barcode', 'Model ID'),
            'owner_id' => Yii::t('lujie/barcode', 'Owner ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return BarcodeQuery the active query used by this AR class.
     */
    public static function find(): BarcodeQuery
    {
        return new BarcodeQuery(static::class);
    }
}
