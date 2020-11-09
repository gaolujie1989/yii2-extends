<?php

namespace lujie\common\address\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;

/**
 * This is the ActiveQuery class for [[AddressPostalCode]].
 *
 * @method AddressPostalCodeQuery id($id)
 * @method AddressPostalCodeQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method AddressPostalCodeQuery addressPostalCodeId($addressPostalCodeId)
 * @method AddressPostalCodeQuery type($type)
 * @method AddressPostalCodeQuery status($status)
 * @method AddressPostalCodeQuery country($country)
 * @method AddressPostalCodeQuery postalCode($postalCode)
 *
 * @method AddressPostalCodeQuery active()
 *
 * @method array getPostalCodes()
 *
 * @method array|AddressPostalCode[] all($db = null)
 * @method array|AddressPostalCode|null one($db = null)
 * @method array|AddressPostalCode[] each($batchSize = 100, $db = null)
 *
 * @see AddressPostalCode
 */
class AddressPostalCodeQuery extends \yii\db\ActiveQuery
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
                    'addressPostalCodeId' => 'address_postal_code_id',
                    'type' => 'type',
                    'status' => 'status',
                    'country' => 'country',
                    'postalCode' => 'postal_code',
                ],
                'queryConditions' => [
                    'active' => ['status' => StatusConst::STATUS_ACTIVE]
                ],
                'queryReturns' => [
                    'getPostalCodes' => ['postal_code', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }
}
