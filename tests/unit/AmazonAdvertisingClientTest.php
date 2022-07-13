<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising\tests\unit;

use lujie\amazon\advertising\AmazonAdvertisingClient;

class AmazonAdvertisingClientTest extends \Codeception\Test\Unit
{
    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $advertisingClient = new AmazonAdvertisingClient();
    }
}
