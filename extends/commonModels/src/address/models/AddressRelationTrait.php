<?php

namespace lujie\common\address\models;

use yii\base\NotSupportedException;
use yii\db\ActiveQuery;

/**
 * Trait AddressRelationTrait
 *
 * @property string $addressClass = Address::class
 * @property Address $address
 * @property Address $billingAddress
 * @property Address $shippingAddress
 *
 * @package lujie\common\address\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait AddressRelationTrait
{
    /**
     * @return array
     * @inheritdoc
     */
    public function addressExtraFields(): array
    {
        $extraFields = [
            'address_id' => 'address',
            'shipping_address_id' => 'shippingAddress',
            'billing_address_id' => 'billingAddress',
        ];
        foreach ($extraFields as $attribute => $extraField) {
            if (!$this->hasAttribute($attribute)) {
                unset($extraFields[$attribute]);
            }
        }
        return array_combine($extraFields, $extraFields);
    }

    /**
     * @param string $addressIdAttribute
     * @return ActiveQuery
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function getAddress(string $addressIdAttribute = 'address_id'): ActiveQuery
    {
        if (!$this->hasAttribute($addressIdAttribute)) {
            throw new NotSupportedException();
        }
        return $this->hasOne($this->addressClass ?? Address::class, ['address_id' => $addressIdAttribute]);
    }

    /**
     * @return ActiveQuery
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function getShippingAddress(): ActiveQuery
    {
        return $this->getAddress('shipping_address_id');
    }

    /**
     * @return ActiveQuery
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function getBillingAddress(): ActiveQuery
    {
        return $this->getAddress('billing_address_id');
    }
}
