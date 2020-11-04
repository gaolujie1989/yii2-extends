<?php

namespace lujie\common\address\models;

use lujie\charging\models\ShippingTableQuery;
use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[AddressPostalCode]].
 *
 * @method AddressPostalCodeQuery id($id)
 * @method AddressPostalCodeQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method AddressPostalCodeQuery addressPostalCodeId($addressPostalCodeId)
 * @method AddressPostalCodeQuery postalCode($postalCode)
 * @method AddressPostalCodeQuery type($type)
 * @method AddressPostalCodeQuery country($country)
 *
 * @method AddressPostalCodeQuery getPostalCodes()
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
                    'postalCode' => 'postal_code',
                    'type' => 'type',
                    'country' => 'country',
                ],
                'queryReturns' => [
                    'getPostalCodes' => ['postal_code', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

    /**
     * @param int $time
     * @return $this
     * @inheritdoc
     */
    public function activeAt(int $time): self
    {
        return $this->andWhere(['<=', 'started_at', $time])->andWhere(['>=', 'ended_at', $time]);
    }
}
