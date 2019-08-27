<?php

namespace lujie\barcode\assigning\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%barcode}}".
 *
 * @property string $barcode_id
 * @property string $code_type EAN or UPC
 * @property string $code_text
 * @property string $assigned_id
 */
class Barcode extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

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
            [['assigned_id'], 'integer'],
            [['code_type'], 'string', 'max' => 3],
            [['code_text'], 'string', 'max' => 13],
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
            'assigned_id' => Yii::t('lujie/barcode', 'Assigned ID'),
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
