<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\tests\unit\address;

use lujie\common\address\AddressPostalCodeChecker;
use lujie\common\tests\unit\fixtures\AddressPostalCodeFixture;

class AddressPostalCodeCheckerTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function _fixtures(): array
    {
        return [
            'AddressPostalCode' => AddressPostalCodeFixture::class
        ];
    }

    /**
     * @inheritdoc
     */
    public function testMe(): void
    {
        $type = 'ISLAND';
        $this->assertTrue(AddressPostalCodeChecker::match($type, 'DE', '18565'));
        $this->assertFalse(AddressPostalCodeChecker::match($type, 'DE', '18566'));

        $this->assertTrue(AddressPostalCodeChecker::match($type, 'EE', '88001'));
        $this->assertTrue(AddressPostalCodeChecker::match($type, 'EE', '88003'));
        $this->assertTrue(AddressPostalCodeChecker::match($type, 'EE', '88005'));
        $this->assertFalse(AddressPostalCodeChecker::match($type, 'EE', '88007'));

        $this->assertTrue(AddressPostalCodeChecker::match($type, 'GB', 'GY5 A12'));
        $this->assertFalse(AddressPostalCodeChecker::match($type, 'GB', 'GB5 A12'));
        $this->assertTrue(AddressPostalCodeChecker::match($type, 'UK', 'GY5 A12'));
        $this->assertFalse(AddressPostalCodeChecker::match($type, 'UK', 'GB5 A12'));

        $this->assertTrue(AddressPostalCodeChecker::match($type, 'GB', 'PA20 369'));
        $this->assertFalse(AddressPostalCodeChecker::match($type, 'GB', 'PA30 369'));
    }
}
