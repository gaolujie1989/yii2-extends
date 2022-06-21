<?php

namespace lujie\as2\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[As2Partner]].
 *
 * @method As2PartnerQuery id($id)
 * @method As2PartnerQuery orderById($sort = SORT_ASC)
 * @method As2PartnerQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method As2PartnerQuery partnerType($partnerType)
 * @method As2PartnerQuery as2Id($as2Id)
 * @method As2PartnerQuery contentType($contentType)
 * @method As2PartnerQuery privateKey($privateKey, bool $like = false)
 * @method As2PartnerQuery compressionType($compressionType)
 * @method As2PartnerQuery status($status)
 *
 * @method As2PartnerQuery createdAtBetween($from, $to = null)
 * @method As2PartnerQuery updatedAtBetween($from, $to = null)
 *
 * @method As2PartnerQuery orderByAs2Id($sort = SORT_ASC)
 * @method As2PartnerQuery orderByCreatedAt($sort = SORT_ASC)
 * @method As2PartnerQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method As2PartnerQuery indexByAs2Id()
 * @method As2PartnerQuery indexByPrivateKey()
 *
 * @method array getAs2Ids()
 * @method array getPrivateKeys()
 *
 * @method array|As2Partner[] all($db = null)
 * @method array|As2Partner|null one($db = null)
 * @method array|As2Partner[] each($batchSize = 100, $db = null)
 *
 * @see As2Partner
 */
class As2PartnerQuery extends \yii\db\ActiveQuery
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
                    'partnerType' => 'partner_type',
                    'as2Id' => 'as2_id',
                    'contentType' => 'content_type',
                    'privateKey' => 'private_key',
                    'compressionType' => 'compression_type',
                    'status' => 'status',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByAs2Id' => 'as2_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByAs2Id' => 'as2_id',
                    'indexByPrivateKey' => 'private_key',
                ],
                'queryReturns' => [
                    'getAs2Ids' => ['as2_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getPrivateKeys' => ['private_key', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
