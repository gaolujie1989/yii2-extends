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
    public static function readXml(string $file, string $valueKey = 'value', int $option = LIBXML_NOCDATA): array
    {
        $xmlReader = new XmlReader();
        $xmlReader->option = $option;
        $xmlReader->valueKey = $valueKey;
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

    /**
     * @param string $content
     * @param int $option
     * @return array
     * @inheritdoc
     */
    public static function readContent(string $content, string $valueKey = 'value', int $option = LIBXML_NOCDATA): array
    {
        $xmlReader = new XmlReader();
        $xmlReader->option = $option;
        $xmlReader->valueKey = $valueKey;
        return $xmlReader->readContent($content);
    }

    /**
     * @param $xml
     * @param string $valueKey
     * @return array
     * @inheritdoc
     */
    public static function toArray(string $xml, string $valueKey = 'value', int $option = LIBXML_NOCDATA): array
    {
        $xmlReader = new XmlReader();
        $xmlReader->option = $option;
        $xmlReader->valueKey = $valueKey;
        return $xmlReader->readContent($xml);
    }
}