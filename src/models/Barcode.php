<?php

namespace lujie\barcode\assigning\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%ean}}".
 *
 * @property string $ean_id
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
            [['code_type'], 'in', 'range' => [self::CODE_TYPE_EAN, self::CODE_TYPE_UPC]],
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
}
