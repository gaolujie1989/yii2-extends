<?php

namespace lujie\common\item\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%item}}".
 *
 * @property int $item_id
 * @property string $item_no
 * @property string $item_type
 * @property array|null $names
 * @property int $weight_g
 * @property int $length_mm
 * @property int $width_mm
 * @property int $height_mm
 * @property string $note
 * @property int $status
 * @property array|null $additional
 *
 * @property ItemBarcode[] $barcodes
 */
class Item extends \lujie\extend\db\ActiveRecord
{
    public const ITEM_BARCODE_CLASS = ItemBarcode::class;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['item_no', 'item_type', 'note'], 'default', 'value' => ''],
            [['names', 'additional'], 'default', 'value' => []],
            [['weight_g', 'length_mm', 'width_mm', 'height_mm', 'status'], 'default', 'value' => 0],
            [['names', 'additional'], 'safe'],
            [['weight_g', 'length_mm', 'width_mm', 'height_mm', 'status'], 'integer'],
            [['item_no'], 'string', 'max' => 50],
            [['item_type'], 'string', 'max' => 20],
            [['note'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'item_id' => Yii::t('lujie/common', 'Item ID'),
            'item_no' => Yii::t('lujie/common', 'Item No'),
            'item_type' => Yii::t('lujie/common', 'Item Type'),
            'names' => Yii::t('lujie/common', 'Names'),
            'weight_g' => Yii::t('lujie/common', 'Weight G'),
            'length_mm' => Yii::t('lujie/common', 'Length Mm'),
            'width_mm' => Yii::t('lujie/common', 'Width Mm'),
            'height_mm' => Yii::t('lujie/common', 'Height Mm'),
            'note' => Yii::t('lujie/common', 'Note'),
            'status' => Yii::t('lujie/common', 'Status'),
            'additional' => Yii::t('lujie/common', 'Additional'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ItemQuery the active query used by this AR class.
     */
    public static function find(): ItemQuery
    {
        return new ItemQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'barcodes' => 'barcodes'
        ]);
    }

    /**
     * @return ActiveQuery|ItemBarcodeQuery
     * @inheritdoc
     */
    public function getBarcodes(): ActiveQuery
    {
        return $this->hasMany(static::ITEM_BARCODE_CLASS, ['item_id' => 'item_id'])->indexBy('code_name');
    }
}
