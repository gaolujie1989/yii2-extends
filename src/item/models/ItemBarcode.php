<?php

namespace lujie\common\item\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%item_barcode}}".
 *
 * @property int $item_barcode_id
 * @property int $item_id
 * @property string $code_name
 * @property string $code_type
 * @property string $code_text
 *
 * @property Item $item
 */
class ItemBarcode extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    public const ITEM_CLASS = Item::class;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%item_barcode}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['item_id'], 'default', 'value' => 0],
            [['code_name', 'code_type'], 'default', 'value' => ''],
            [['item_id'], 'integer'],
            [['code_text'], 'required'],
            [['code_name', 'code_type'], 'string', 'max' => 20],
            [['code_text'], 'string', 'max' => 50],
            [['code_text'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'item_barcode_id' => Yii::t('lujie/common', 'Item Barcode ID'),
            'item_id' => Yii::t('lujie/common', 'Item ID'),
            'code_name' => Yii::t('lujie/common', 'Code Name'),
            'code_type' => Yii::t('lujie/common', 'Code Type'),
            'code_text' => Yii::t('lujie/common', 'Code Text'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ItemBarcodeQuery the active query used by this AR class.
     */
    public static function find(): ItemBarcodeQuery
    {
        return new ItemBarcodeQuery(static::class);
    }

    /**
     * @return ActiveQuery|ItemQuery
     * @inheritdoc
     */
    public function getItem(): ActiveQuery
    {
        return $this->hasOne(static::ITEM_CLASS, ['item_id' => 'item_id']);
    }
}
