<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;


class PlentyMarketAddressFormatter
{
    /**
     * @param array $address
     * @return array
     * @inheritdoc
     */
    public static function format(array $address): array
    {
        if (empty($address['name3']) && ($pos = strrpos($address['name2'], ' ')) !== false) {
            $address['name3'] = substr($address['name2'], $pos);
            $address['name2'] = substr($address['name2'], 0, $pos);
        }
        if (empty($address['address2']) && !empty($address['address3']) && is_numeric($address['address1'])) {
            [$address['address2'], $address['address3'], $address['address1']] = [$address['address1'], $address['address2'], $address['address3']];
        }
        if (empty($address['address2']) && preg_match('/\d+/', $address['address1'], $matches, PREG_OFFSET_CAPTURE)) {
            $numberPos = $matches[0][1];
            if ($numberPos > 0 && !static::isUK($address)) {
                $address['address2'] = substr($address['address1'], $numberPos);
                $address['address1'] = substr($address['address1'], 0, $numberPos);
            } else if (($pos = strpos($address['address1'], ' ', $numberPos)) !== false) {
                $address['address2'] = substr($address['address1'], 0, $pos);
                $address['address1'] = substr($address['address1'], $pos);
            } else {
                $numberLength = strlen($matches[0][0]);
                $address['address2'] = substr($address['address1'], 0, $numberPos + $numberLength);
                $address['address1'] = substr($address['address1'], $numberPos + $numberLength);
            }
        }
        return array_map('trim', $address);
    }

    public static function isUK(array $address): bool
    {
        return (isset($address['country']) && in_array($address['country'], ['UK', 'GB'], true))
            || (isset($address['countryId']) && $address['countryId'] === 12);
    }
}