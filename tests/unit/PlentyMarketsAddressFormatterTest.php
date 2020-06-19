<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit;

use lujie\plentyMarkets\PlentyMarketAddressFormatter;

class PlentyMarketsAddressFormatterTest extends \Codeception\Test\Unit
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
     * @inheritdoc
     */
    public function testMe(): void
    {
        $addresses = require_once __DIR__ . '/addresses.php';
        foreach ($addresses as [$address, $exceptedAddress]) {
            if (is_array($address) && $address) {
                $address = array_map('trim', $address);
                $exceptedAddress = array_map('trim', $exceptedAddress);
                $this->assertEquals($exceptedAddress, PlentyMarketAddressFormatter::format($address));
            }
        }
    }
}
