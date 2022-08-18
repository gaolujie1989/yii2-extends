<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\file\FileReaderInterface;
use SimpleXMLElement;
use yii\base\BaseObject;

/**
 * Class XmlReader
 * @package lujie\extend\file\readers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class XmlReader extends BaseObject implements FileReaderInterface
{
    /**
     * @var int
     */
    public $option = LIBXML_NOCDATA;

    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    public function read(string $file): array
    {
        $contents = file_get_contents($file);
        $xml = simplexml_load_string($contents, 'SimpleXMLElement', $this->option);
        return $this->toArray($xml);
    }

    /**
     * @param SimpleXMLElement $xml
     * @param string $valueKey
     * @return array
     * @inheritdoc
     */
    public function toArray(SimpleXMLElement $xml, string $valueKey = 'value'): array
    {
        $result = (array) $xml;
        foreach ($xml as $key => $value) {
            if (!is_scalar($result[$key])) {
                $result[$key] = $this->toArray($result[$key], $valueKey);
            }
            if ($value instanceof SimpleXMLElement) {
                $attributes = (array)$value->attributes();
                if (isset($attributes['@attributes'])) {
                    $result[$key] = array_merge(
                        $attributes['@attributes'],
                        is_array($result[$key]) ? $result[$key] : [$valueKey => $result[$key]]
                    );
                }
            }
        }
        return $result;
    }
}
