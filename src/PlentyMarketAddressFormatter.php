<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

/**
 * Class PlentyMarketAddressFormatter
 * @package lujie\plentyMarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PlentyMarketAddressFormatter
{
    public static $minStreetLength = 5;

    /**
     * @param array $address
     * @return array
     * @inheritdoc
     */
    public static function format(array $address): array
    {
        //fix first name and last name
        if (empty($address['name3']) && ($pos = strrpos($address['name2'], ' ')) !== false) {
            $address['name3'] = substr($address['name2'], $pos);
            $address['name2'] = substr($address['name2'], 0, $pos);
        }
        //fix company name
        for ($i = 1; $i <= 3; $i++) {
            $field = 'address' . $i;
            if (strpos(strtolower($address[$field]), 'gmbh') !== false) {
                $name1 = $address['name1'];
                $address['name1'] = $address[$field];
                $address[$field] = $name1;
                $address = self::validateAddress1($address);
                break;
            }
        }
        if (empty($address['address2']) && !empty($address['address3'])) {
            [$additional3, $address['address3']] = self::extractAdditionalFromAddress($address['address3']);
            [$address['address2'], $address['address3']] = self::extractNumberFromAddress($address['address3'], static::isUK($address));
        }
        if (empty($address['address2']) && !empty($address['address1'])) {
            [$additional1, $address['address1']] = self::extractAdditionalFromAddress($address['address1']);
            [$address['address2'], $address['address1']] = self::extractNumberFromAddress($address['address1'], static::isUK($address));
            $address = self::validateAddress1($address);
        }
        if (empty($address['address2'])) {
            $address['address2'] = '*';
        } else {
            [$address['address2'], $additional2] = self::extractNumberFromAddress($address['address2'], true);
            $address['address2'] = strtr(trim($address['address2']), [' ' => '-']);
        }
        $address['address3'] .= ($additional1 ?? '') . ($additional2 ?? '') . ($additional3 ?? '');
        return array_map('trim', $address);
    }

    /**
     * @param string $address
     * @param bool $numberInLeft
     * @return array|string[]  [streetNo, street]
     * @inheritdoc
     */
    public static function extractNumberFromAddress(string $address, bool $numberInLeft = true): array
    {
        if (preg_match('/\w*\d+(\S*\s+(\S{1,2}\s)?)?/', $address , $matches, PREG_OFFSET_CAPTURE)) {
            $addressLength = strlen($address);
            $numberPos = $matches[0][1];
            $numberLength = strlen($matches[0][0]);
            $numberEndPos = $numberPos + $numberLength;
//            $numberEndPos = strpos($address, ' ', $numberPos) ?: $addressLength;
            if ($numberPos === 0 || ($numberInLeft && $numberEndPos !== $addressLength)) {
                if ($addressLength - $numberEndPos < self::$minStreetLength) {
                    return [$address, ''];
                }
                $streetNo = substr($address, 0, $numberEndPos);
                $street = substr($address, $numberEndPos);
                return [trim($streetNo), trim($street)];
            } else {
                if ($numberPos < self::$minStreetLength) {
                    return [$address, ''];
                }
                $streetNo = substr($address, $numberPos);
                $street = substr($address, 0, $numberPos);
                return [trim($streetNo), trim($street)];
            }
        }
        return ['', $address];
    }

    /**
     * @param string $address
     * @return array|string[]  [additional, street]
     */
    public static function extractAdditionalFromAddress(string $address): array
    {
        if (preg_match('/(flat)[\s-]*\d+\w*/i', $address, $matches)) {
            return [strtr(trim($matches[0]), [' ' => '-']), trim(strtr($address, [$matches[0] => '']))];
        }
        return ['', $address];
    }

    /**
     * @param array $address
     * @return bool
     * @inheritdoc
     */
    public static function isUK(array $address): bool
    {
        return (isset($address['country']) && in_array($address['country'], ['UK', 'GB'], true))
            || (isset($address['countryId']) && $address['countryId'] === 12);
    }

    /**
     * @param array $address
     * @return array
     * @inheritdoc
     */
    public static function validateAddress1(array $address): array
    {
        if (empty($address['address1'])) {
            if (!empty($address['address3'])) {
                $address['address1'] = $address['address3'];
                $address['address3'] = '';
            } else {
                $address['address1'] = $address['address2'];
                $address['address2'] = '';
            }
        }
        return $address;
    }
}