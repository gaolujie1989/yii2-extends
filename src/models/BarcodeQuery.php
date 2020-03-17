<?php

namespace lujie\barcode\assigning\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Barcode]].
 *
 * @method BarcodeQuery id($id)
 * @method BarcodeQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method BarcodeQuery barcodeId($barcodeId)
 * @method BarcodeQuery codeType($codeType)
 * @method BarcodeQuery codeText($codeText)
 * @method BarcodeQuery assignedId($assignedId)
 *
 * @method BarcodeQuery ean()
 * @method BarcodeQuery upc()
 * @method BarcodeQuery unassigned()
 *
 * @method array|Barcode[] all($db = null)
 * @method array|Barcode|null one($db = null)
 * @method array|Barcode[] each($batchSize = 100, $db = null)
 *
 * @see Barcode
 */
class BarcodeQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'barcodeId' => 'barcode_id',
                    'codeType' => 'code_type',
                    'codeText' => 'code_text',
                    'assignedId' => 'assigned_id',
                ],
                'queryConditions' => [
                    'ean' => ['code_type' => Barcode::CODE_TYPE_EAN],
                    'upc' => ['code_type' => Barcode::CODE_TYPE_UPC],
                    'unassigned' => ['assigned_id' => 0],
                ],
            ]
        ]);
    }
}
