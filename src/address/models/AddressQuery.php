<?php

namespace lujie\common\address\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Address]].
 *
 * @method AddressQuery id($id)
 * @method AddressQuery orderById($sort = SORT_ASC)
 * @method AddressQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method AddressQuery addressId($addressId)
 * @method AddressQuery postalCode($postalCode, bool|string $like = false)
 *
 * @method AddressQuery createdAtBetween($from, $to = null)
 * @method AddressQuery updatedAtBetween($from, $to = null)
 *
 * @method AddressQuery orderByAddressId($sort = SORT_ASC)
 * @method AddressQuery orderByCreatedAt($sort = SORT_ASC)
 * @method AddressQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method AddressQuery indexByAddressId()
 * @method AddressQuery indexByPostalCode()
 *
 * @method array getAddressIds()
 * @method array getPostalCodes()
 *
 * @method array|Address[] all($db = null)
 * @method array|Address|null one($db = null)
 * @method array|Address[] each($batchSize = 100, $db = null)
 *
 * @see Address
 */
class AddressQuery extends \yii\db\ActiveQuery
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
                    'addressId' => 'address_id',
                    'postalCode' => 'postal_code',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByAddressId' => 'address_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByAddressId' => 'address_id',
                    'indexByPostalCode' => 'postal_code',
                ],
                'queryReturns' => [
                    'getAddressIds' => ['address_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getPostalCodes' => ['postal_code', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
