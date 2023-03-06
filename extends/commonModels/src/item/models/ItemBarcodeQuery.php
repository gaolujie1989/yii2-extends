<?php

namespace lujie\common\item\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[ItemBarcode]].
 *
 * @method ItemBarcodeQuery id($id)
 * @method ItemBarcodeQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ItemBarcodeQuery itemBarcodeId($itemBarcodeId)
 * @method ItemBarcodeQuery itemId($itemId)
 * @method ItemBarcodeQuery notItemId($itemId)
 * @method ItemBarcodeQuery codeType($codeType)
 * @method ItemBarcodeQuery codeName($codeName)
 * @method ItemBarcodeQuery codeText($codeText)
 *
 * @method array|ItemBarcode[] all($db = null)
 * @method array|ItemBarcode|null one($db = null)
 * @method array|ItemBarcode[] each($batchSize = 100, $db = null)
 *
 * @see ItemBarcode
 */
class ItemBarcodeQuery extends \yii\db\ActiveQuery
{

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'itemBarcodeId' => 'item_barcode_id',
                    'itemId' => 'item_id',
                    'notItemId' => ['item_id' => '!='],
                    'codeType' => 'code_type',
                    'codeName' => 'code_name',
                    'codeText' => 'code_text',
                ]
            ]
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getItemIds(bool $indexByCodeText = true): array
    {
        if ($indexByCodeText) {
            $this->indexBy('code_text');
        }
        return $this->select(['item_id'])->column();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getItemNos(bool $indexByCodeText = true): array
    {
        if ($indexByCodeText) {
            $this->indexBy('code_text');
        }
        return $this->innerJoinWith(['item'])->select(['item_no'])->column();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getCodeTexts(bool $indexByItemId = true): array
    {
        if ($indexByItemId) {
            $this->indexBy('item_id');
        }
        return $this->select(['code_text'])->column();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getItemBarcodeTexts(): array
    {
        $barcodes = $this->select(['item_id', 'code_name', 'code_text'])->asArray()->all();
        return ArrayHelper::map($barcodes, 'code_name', 'code_text', 'item_id');
    }
}
