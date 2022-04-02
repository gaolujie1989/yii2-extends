<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\transformers;

use lujie\data\exchange\transformers\TransformerInterface;
use yii\base\BaseObject;

/**
 * Class ShippingTableTransformer
 * @package lujie\charging\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableImportTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        $keys = array_keys(reset($data));
        $destinations = array_filter($keys, static function (string $key) {
            return strlen($key) === 2 && preg_match('/[A-Z]{2}/', $key);
        });
        if (empty($destinations)) {
            return $data;
        }
        $transformed = [];
        foreach ($data as $values) {
            $destinationPrices = [];
            foreach ($destinations as $destination) {
                if (!empty($values[$destination])) {
                    $destinationPrices[$destination] = $values[$destination];
                }
                unset($values[$destination]);
            }
            foreach ($destinationPrices as $destination => $priceStr) {
                if (preg_match('/(\d+[\.,]\d+)\s+([a-zA-Z]{3})/', $priceStr, $matches)) {
                    $transformed[] = array_merge($values, [
                        'destination' => $destination,
                        'price' => strtr($matches[1], [',' => '.']),
                        'currency' => $matches[2],
                    ]);
                }
            }
        }
        return $transformed;
    }
}