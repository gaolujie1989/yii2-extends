<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\item\forms;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\common\item\models\Item;
use lujie\common\item\models\ItemBarcode;
use lujie\extend\db\FormTrait;

/**
 * Class ItemForm
 * @package lujie\common\item\models\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ItemForm extends Item
{
    use FormTrait;

    public $ean;

    public $ownSKU;

    /**
     * [
     *      'attribute' => ['code_name', 'code_type']
     * ]
     * @var array
     */
    public $barcodeConfig = [
        'ean' => ['EAN', 'EAN13'],
        'ownSKU' => ['OWN', 'CODE128'],
    ];

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = $this->formRules();
        return array_merge($rules, [
            [array_keys($this->barcodeConfig), 'validateBarcode'],
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge($this->formBehaviors(), [
            'relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['barcodes'],
                'indexKeys' => [
                    'barcodes' => 'code_name'
                ]
            ],
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['barcodes'],
            ]
        ]);
    }

    /**
     * @param string $attribute
     * @inheritdoc
     */
    public function validateBarcode(string $attribute): void
    {
        $itemBarcodeQuery = ItemBarcode::find()->codeText($this->{$attribute});
        if (!$this->getIsNewRecord()) {
            $itemBarcodeQuery->notItemId($this->item_id);
        }
        if ($itemBarcodeQuery->exists()) {
            $this->addError($attribute, 'Barcode Already Exists');
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        $barcodeKeys = array_keys($this->barcodeConfig);
        $barcodeKeys = array_combine($barcodeKeys, $barcodeKeys);
        return array_merge($this->aliasFields(), $barcodeKeys);
    }

    #region CorrectSize Before Save

    /**
     * @inheritdoc
     */
    public function correctSize(): void
    {
        $sizes = [$this->height_mm, $this->width_mm, $this->length_mm];
        sort($sizes, SORT_NUMERIC);
        [$this->height_mm, $this->width_mm, $this->length_mm] = $sizes;
    }

    /**
     * @param bool $insert
     * @return bool
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        $this->correctSize();
        return parent::beforeSave($insert);
    }

    #endregion

    #region UpdateBarcodes After Save

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        $this->updateBarcodes();
    }

    /**
     * if barcode is null, client may not update, so do nothing
     * if barcode is empty, delete barcode, else update it
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    protected function updateBarcodes(): void
    {
        foreach ($this->barcodeConfig as $attribute => [$codeName, $codeType]) {
            $value = $this->{$attribute};
            if ($value === null) {
                continue;
            } elseif ($value === '') {
                $this->removeBarcode($codeName);
            } elseif ($value) {
                $itemBarcode = new ItemBarcode([
                    'item_id' => $this->item_id,
                    'code_name' => $codeName,
                    'code_type' => $codeType,
                    'code_text' => $value,
                ]);
                $this->saveBarcode($itemBarcode);
            }
        }
    }

    /**
     * @param ItemBarcode $itemBarcode
     * @return ItemBarcode
     * @inheritdoc
     */
    protected function saveBarcode(ItemBarcode $itemBarcode): ItemBarcode
    {
        $saveBarcode = $this->barcodes[$itemBarcode->code_name] ?? $itemBarcode;
        if ($saveBarcode !== $itemBarcode) {
            $saveBarcode->code_type = $itemBarcode->code_type;
            $saveBarcode->code_text = $itemBarcode->code_text;
        }
        $saveBarcode->save(false);
        return $saveBarcode;
    }

    /**
     * @param string $codeName
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    protected function removeBarcode(string $codeName): int
    {
        $existBarcode = $this->barcodes[$codeName] ?? null;
        if ($existBarcode !== null) {
            return $existBarcode->delete();
        }
        return -1;
    }

    #endregion
}
