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
        $this->assertIsString($methodDoc);
    }
}
