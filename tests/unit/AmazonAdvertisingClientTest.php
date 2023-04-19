<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising\tests\unit;

use lujie\amazon\advertising\AmazonAdvertisingClient;
use PHPUnit\Framework\TestCase;

class AmazonAdvertisingClientTest extends TestCase
{
    /**
     * @inheritdoc
     */
    public function testMe(): void
    {
        $advertisingClient = new AmazonAdvertisingClient();
        $methodDoc = $advertisingClient->methodDoc();
        file_put_contents(dirname(__DIR__) . '/_output/methodDoc.txt', $methodDoc);
        $this->assertIsString($methodDoc);
    }
}
