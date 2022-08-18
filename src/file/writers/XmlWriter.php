<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\writers;

use lujie\extend\file\FileWriterInterface;
use SimpleXMLElement;
use yii\base\BaseObject;
use yii\helpers\FileHelper;

/**
 * Class XmlReader
 * @package lujie\extend\file\readers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class XmlWriter extends BaseObject implements FileWriterInterface
{
    /**
     * @param string $file
     * @param array $data
     * @throws \yii\base\Exception
     * @throws \Exception
     * @inheritdoc
     */
    public function write(string $file, array $data): void
    {
        FileHelper::createDirectory(dirname($file));
        file_put_contents($file, $this->toXml($data)->asXML());
    }

    /**
     * @param array $data
     * @param string $root
     * @param SimpleXMLElement|null $xml
     * @return SimpleXMLElement
     * @throws \Exception
     * @inheritdoc
     */
    public function toXml(array $data, string $root = '<root/>', SimpleXMLElement $xml = null): SimpleXMLElement
    {
        if ($xml === null) {
            $xml = new SimpleXMLElement($root);
        }

        foreach ($data as $key => $value) {
            // If there is nested array then
            if (is_array($value)) {
                $this->toXml($value, $key, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }

        return $xml;
    }
}
