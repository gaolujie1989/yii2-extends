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
    public $destinationSeparators = ['/', '|', ',', ';'];

    public $defaultCurrency = 'EUR';

    /**
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        $keys = array_keys(reset($data));
        $destValueKeys = [];
        foreach ($keys as $key) {
            if (strlen($key) === 2 && preg_match('/[A-Z]{2}/', $key)) {
                $destValueKeys[$key] = $key;
                continue;
            }
            foreach ($this->destinationSeparators as $separator) {
                if (strpos($key, $separator) !== false) {
                    $keyParts = array_map('trim', explode($separator, $key));
                    foreach ($keyParts as $keyPart) {
                        if (strlen($keyPart) === 2 && preg_match('/[A-Z]{2}/', $keyPart)) {
                            $destValueKeys[$keyPart] = $key;
                        }
                    }
                    break;
                }
            }
        }
        if (empty($destValueKeys)) {
            return $data;
        }
        $transformed = [];
        foreach ($data as $values) {
            $destinationPrices = [];
            foreach ($destValueKeys as $destination => $valueKey) {
                if (!empty($values[$valueKey])) {
                    $destinationPrices[$destination] = $values[$valueKey];
                }
            }
            $values = array_diff_key($values, array_flip($destValueKeys));
            foreach ($destinationPrices as $destination => $priceStr) {
                if (preg_match('/(\d+[\.,]\d+)\s*([a-zA-Z]{3})?/', $priceStr, $matches)) {
                    $transformed[] = array_merge($values, [
                        'destination' => $destination,
                        'price' => strtr($matches[1], [',' => '.']),
                        'currency' => $matches[2] ?? $this->defaultCurrency,
                    ]);
                }
            }
        }
        return $transformed;
    }
}