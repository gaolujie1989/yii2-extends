<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\file\FileReaderInterface;
use Spatie\PdfToText\Pdf;
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
        return $this->readXml($contents);
    }

    /**
     * @param $xml
     * @param string $valueKey
     * @return array
     * @inheritdoc
     */
    public function readXml($xml, string $valueKey = 'value'): array
    {
        if (is_string($xml)) {
            $xml = simplexml_load_string($xml, 'SimpleXMLElement', $this->option);
        }
        $result = (array) $xml;
        foreach ($xml as $key => $value) {
            if (!is_scalar($result[$key])) {
                $result[$key] = $this->readXml($result[$key], $valueKey);
            }
            if ($value instanceof \SimpleXMLElement) {
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
