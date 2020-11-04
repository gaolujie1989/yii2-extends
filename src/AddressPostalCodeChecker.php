<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\address;


use lujie\common\address\models\AddressPostalCode;
use yii\helpers\StringHelper;

class AddressPostalCodeChecker
{
    /**
     * @param string $type
     * @param string $country
     * @param string $postalCode
     * @param int $time
     * @return bool
     */
    public static function match(string $type, string $country, string $postalCode, int $time = 0): bool
    {
        if (in_array($country, ['UK', 'GB'])) {
            $country = ['UK', 'GB'];
        }
        $query = AddressPostalCode::find()
            ->type($type)
            ->country($country)
            ->activeAt($time ?: time());
        $exists = (clone $query)->postalCode($postalCode)->exists();
        if ($exists) {
            return true;
        }

        $typePostalCodes = $query->getPostalCodes();
        if (empty($typePostalCodes)) {
            return false;
        }

        foreach ($typePostalCodes as $typePostalCode) {
            if (strpos($typePostalCode, '-') !== false) {
                $typePostalCodeRange = array_map('trim', explode('-', $typePostalCode));
                $postalCodePrefix = substr($postalCode, 0, strlen($typePostalCodeRange[0]));
                if ($typePostalCodeRange[0] <= $postalCodePrefix && $postalCodePrefix <= $typePostalCodeRange[1]) {
                    return true;
                }
            } else if (StringHelper::matchWildcard($typePostalCode, $postalCode)) {
                return true;
            }
        }

        return false;
    }
}