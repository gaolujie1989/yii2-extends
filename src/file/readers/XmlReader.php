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
        $content = file_get_contents($file);
        return $this->readContent($content);
    }

    /**
     * @param string $content
     * @return array
     * @inheritdoc
     */
    public function readContent(string $content): array
    {
        $xml = simplexml_load_string($content, 'SimpleXMLElement', $this->option);
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
