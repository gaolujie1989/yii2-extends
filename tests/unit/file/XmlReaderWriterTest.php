<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\file;

use lujie\extend\file\readers\XmlReader;

class XmlReaderWriterTest extends \Codeception\Test\Unit
{
    public function testRead(): void
    {
        $xmlReader = new XmlReader();
        $data = $xmlReader->read(dirname(__DIR__) . '/fixtures/data/xml.xml');
        codecept_debug($data);
    }
}