<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising\tests\unit;

use lujie\amazon\advertising\AmazonAdvertisingClient;

class AmazonAdvertisingClientTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $advertisingClient = new AmazonAdvertisingClient();
//        codecept_debug($advertisingClient->generateMethodDoc());
    }
}
