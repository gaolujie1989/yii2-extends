<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\item\models\forms;

use lujie\alias\behaviors\UnitAliasBehavior;
use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\common\item\models\Item;
use lujie\common\item\models\ItemBarcode;
use lujie\extend\helpers\ModelHelper;

/**
 * Class ItemForm
 * @package lujie\common\item\models\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ItemForm extends Item
{
    public $ean;

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
        $rules = parent::rules();
        ModelHelper::removeAttributesRules($rules, ['weight_g', 'length_mm', 'width_mm', 'height_mm']);
        return array_merge($rules, [
            [['weight_kg', 'length_cm', 'width_cm', 'height_cm'], 'number', 'min' => 0],
            [[array_keys($this->barcodeConfig)], 'validateBarcode'],
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
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
            ],
            'unitG2KG' => [
                'class' => UnitAliasBehavior::class,
                'aliasProperties' => [
                    'weight_kg' => 'weight_g',
                ],
                'baseUnit' => UnitAliasBehavior::UNIT_WEIGHT_G,
                'displayUnit' => UnitAliasBehavior::UNIT_WEIGHT_KG,
            ],
            'unitMM2CM' => [
                'class' => UnitAliasBehavior::class,
                'aliasProperties' => [
                    'length_cm' => 'length_mm',
                    'width_cm' => 'width_mm',
                    'height_cm' => 'height_mm',
                ],
                'baseUnit' => UnitAliasBehavior::UNIT_SIZE_MM,
                'displayUnit' => UnitAliasBehavior::UNIT_SIZE_CM,
            ],
        ]);
    }

    public function validateBarcodes(string $attribute): void
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
        return array_merge(parent::fields(), $barcodeKeys, [
            'weight_kg' => 'weight_kg',
            'length_cm' => 'length_cm',
            'width_cm' => 'width_cm',
            'height_cm' => 'height_cm',
        ]);
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
     * @throws \yii\db\Exception
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
     * @inheritdoc
     */
    protected function updateBarcodes(): void
    {
        foreach ($this->barcodeConfig as $attribute => [$codeName, $codeType]) {
            $value = $this->{$attribute};
            if ($value === null) {
                continue;
            } else if ($value === '') {
                $this->removeBarcode($codeName);
            } else if ($value) {
                $itemBarcode = new ItemBarcode([
                    'item_id' => $this->item_id,
                    'code_name' => $codeName,
                    'code_type' => $codeType,
                    'code_text' => $this->ean,
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
        $saveBarcode = $this->itemBarcodes[$itemBarcode->code_name] ?? $itemBarcode;
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
     * @inheritdoc
     */
    protected function removeBarcode(string $codeName): int
    {
        $existBarcode = $this->itemBarcodes[$codeName] ?? null;
        if ($existBarcode !== null) {
            return $existBarcode->delete();
        }
        return -1;
    }

    #endregion
}