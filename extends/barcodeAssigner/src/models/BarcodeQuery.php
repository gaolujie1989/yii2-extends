<?php

namespace lujie\barcode\assigning\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Barcode]].
 *
 * @method BarcodeQuery id($id)
 * @method BarcodeQuery orderById($sort = SORT_ASC)
 * @method BarcodeQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method BarcodeQuery barcodeId($barcodeId)
 * @method BarcodeQuery codeType($codeType)
 * @method BarcodeQuery codeText($codeText)
 * @method BarcodeQuery modelType($modelType)
 * @method BarcodeQuery modelId($modelId)
 * @method BarcodeQuery ownerId($ownerId)
 *
 * @method BarcodeQuery createdAtBetween($from, $to = null)
 * @method BarcodeQuery updatedAtBetween($from, $to = null)
 *
 * @method BarcodeQuery assigned()
 * @method BarcodeQuery unassigned()
 *
 * @method BarcodeQuery orderByBarcodeId($sort = SORT_ASC)
 * @method BarcodeQuery orderByModelId($sort = SORT_ASC)
 * @method BarcodeQuery orderByOwnerId($sort = SORT_ASC)
 * @method BarcodeQuery orderByCreatedAt($sort = SORT_ASC)
 * @method BarcodeQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method BarcodeQuery indexByBarcodeId()
 * @method BarcodeQuery indexByModelId()
 * @method BarcodeQuery indexByOwnerId()
 *
 * @method array getBarcodeIds()
 * @method array getModelIds()
 * @method array getOwnerIds()
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
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'barcodeId' => 'barcode_id',
                    'codeType' => 'code_type',
                    'codeText' => 'code_text',
                    'modelType' => 'model_type',
                    'modelId' => 'model_id',
                    'ownerId' => 'owner_id',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [
                    'assigned' => ['!=', 'model_id' => 0],
                    'unassigned' => ['model_id' => 0],
                ],
                'querySorts' => [
                    'orderByBarcodeId' => 'barcode_id',
                    'orderByModelId' => 'model_id',
                    'orderByOwnerId' => 'owner_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByBarcodeId' => 'barcode_id',
                    'indexByModelId' => 'model_id',
                    'indexByOwnerId' => 'owner_id',
                ],
                'queryReturns' => [
                    'getBarcodeIds' => ['barcode_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getModelIds' => ['model_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getOwnerIds' => ['owner_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
