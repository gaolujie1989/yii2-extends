<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\order\center\forms;

use lujie\sales\order\center\models\Address;

/**
 * Class AddressForm
 * @package lujie\sales\order\center\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AddressForm extends Address
{
    /**
     * if identify by signature,
     * no update for exists address, create address instead
     * if signature is same, not create address, return exist address instead
     * @var bool
     */
    public $identifyBySignature = true;

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null): bool
    {
        if ($this->identifyBySignature) {
            $signature = $this->generateSignature();
            $address = static::findBySignature($signature);
            if ($address === null) {
                $this->setIsNewRecord(true);
                return parent::save();
            }
            $this->refreshInternal($address);
            return true;
        }
        return parent::save();
    }
}
