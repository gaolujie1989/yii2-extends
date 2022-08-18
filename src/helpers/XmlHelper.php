<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use lujie\extend\file\readers\XmlReader;
use lujie\extend\file\writers\XmlWriter;

/**
 * Class XmlHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class XmlHelper
{
    /**
     * @param string $file
     * @param int $option
     * @return array
     * @inheritdoc
     */
    public static function readXml(string $file, int $option = LIBXML_NOCDATA): array
    {
        $xmlReader = new XmlReader();
        $xmlReader->option = $option;
        return $xmlReader->read($file);
    }

    /**
     * @param string $file
     * @param array $data
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public static function writeXml(string $file, array $data): void
    {
        $xmlWriter = new XmlWriter();
        $xmlWriter->write($file, $data);
    }

    public static function readContent(): array
    {

    }

    /**
     * @param string|\SimpleXMLElement $xml
     * @return array
     * @inheritdoc
     */
    public static function toArray($xml, $valueKey = 'value'): array
    {
    }
}