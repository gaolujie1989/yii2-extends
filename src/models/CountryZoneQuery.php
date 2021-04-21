<?php

namespace lujie\charging\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[CountryZone]].
 *
 * @method CountryZoneQuery id($id)
 * @method CountryZoneQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method CountryZoneQuery countryZoneId($countryZoneId)
 * @method CountryZoneQuery ownerId($ownerId)
 * @method CountryZoneQuery carrier($carrier)
 * @method CountryZoneQuery country($country)
 *
 * @method string|null|false getZone()
 *
 * @method array|CountryZone[] all($db = null)
 * @method array|CountryZone|null one($db = null)
 * @method array|CountryZone[] each($batchSize = 100, $db = null)
 *
 * @see CountryZone
 */
class CountryZoneQuery extends \yii\db\ActiveQuery
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
                    'countryZoneId' => 'country_zone_id',
                    'ownerId' => 'owner_id',
                    'carrier' => 'carrier',
                    'country' => 'country',
                ],
                'queryReturn' => [
                    'getZone' => ['zone', FieldQueryBehavior::RETURN_SCALAR]
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

    /**
     * @param string $postalCode
     * @return $this
     * @inheritdoc
     */
    public function postalCode(string $postalCode): self
    {
        return $this->andWhere(['<=', 'postal_code_from', $postalCode])->andWhere(['>=', 'postal_code_to', $postalCode]);
    }
}
