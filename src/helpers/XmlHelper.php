<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

/**
 * Class XmlHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class XmlHelper
{
    /**
     * @param string|\SimpleXMLElement $xml
     * @return array
     * @inheritdoc
     */
    public static function toArray($xml, $valueKey = 'value'): array
    {
        if (is_string($xml)) {
            $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        $result = (array) $xml;
        foreach ($xml as $key => $value) {
            if (!is_scalar($result[$key])) {
                $result[$key] = static::toArray($value, $valueKey);
            }
            $attributes = (array)$value->attributes();
            if (isset($attributes['@attributes'])) {
                $result[$key] = array_merge(
                    $attributes['@attributes'],
                    is_array($result[$key]) ? $result[$key] : [$valueKey => $result[$key]]
                );
            }
        }
        return $result;
    }
}