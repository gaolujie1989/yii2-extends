<?php

namespace lujie\barcode\assigning\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Barcode]].
 *
 * @method BarcodeQuery codeText($codeText)
 * @method BarcodeQuery assignedId($assignedId)
 *
 * @method BarcodeQuery ean()
 * @method BarcodeQuery upc()
 * @method BarcodeQuery unassigned()
 *
 * @method Barcode[]|array all($db = null)
 * @method Barcode|array|null one($db = null)
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
