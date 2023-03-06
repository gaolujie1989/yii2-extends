<?php

namespace lujie\charging\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[ShippingZone]].
 *
 * @method ShippingZoneQuery id($id)
 * @method ShippingZoneQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ShippingZoneQuery shippingZoneId($shippingZoneId)
 * @method ShippingZoneQuery ownerId($ownerId)
 * @method ShippingZoneQuery carrier($carrier);
 * @method ShippingZoneQuery departure($departure);
 * @method ShippingZoneQuery destination($destination);
 * @method ShippingZoneQuery zone($zone);
 *
 * @method array|ShippingZone[] all($db = null)
 * @method array|ShippingZone|null one($db = null)
 * @method array|ShippingZone[] each($batchSize = 100, $db = null)
 *
 * @see ShippingZone
 */
class ShippingZoneQuery extends \yii\db\ActiveQuery
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
                    'shippingZoneId' => 'shipping_zone_id',
                    'ownerId' => 'owner_id',
                    'carrier' => 'carrier',
                    'departure' => 'departure',
                    'destination' => 'destination',
                    'zone' => 'zone',
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

    /**
     * @param false $fillEmpty
     * @return array
     * @inheritdoc
     */
    public function getCarrierZones(array $carriers = [], bool $keepEmpty = true): array
    {
        $carrierZones = $this->andFilterWhere(['carrier' => $carriers])
            ->select(['zone'])
            ->indexBy('carrier')
            ->column();
        if ($carriers && $keepEmpty) {
            $carrierZones = array_merge(array_fill_keys($carriers, ''), $carrierZones);
        }
        return $carrierZones;
    }
}
