<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\address\tests\unit;


use lujie\common\address\forms\AddressForm;
use lujie\common\address\models\Address;

class AddressFormTest extends \Codeception\Test\Unit
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

    public function testMe(): void
    {
        $data = [
            'country' => 'DE',
            'state' => '',
            'city' => 'Berlin',
            'name1' => 'CCLIFE Tech',
            'name2' => 'Lujie',
            'name3' => 'Zhou',
            'address1' => 'XXX Road',
            'address2' => '21',
            'address3' => 'under the door',
            'zip_code' => '625321',
            'email' => 'xxx@xxx.com',
            'phone' => '12345678901',
        ];
        $aliasData = [
            'companyName' => 'CCLIFE Tech',
            'firstName' => 'Lujie',
            'lastName' => 'Zhou',
            'street' => 'XXX Road',
            'houseNo' => '21',
            'additional' => 'under the door',
        ];
        $query = Address::find();
        $addressForm = new AddressForm();
        $addressForm->setAttributes($aliasData);
        $this->assertEquals($aliasData, $addressForm->getAttributes(array_keys($aliasData)));

        $addressForm->setAttributes($data);
        $this->assertTrue($addressForm->save());
        $this->assertEquals(1, $query->count());
        $addressId1 = $addressForm->address_id;

        $addressForm->address1 = 'xxx street';
        $this->assertTrue($addressForm->save());
        $this->assertEquals(2, $query->count());
        $addressId2 = $addressForm->address_id;
        $this->assertNotEquals($addressId1, $addressId2);

        $addressForm->address1 = 'XXX Road';
        $this->assertTrue($addressForm->save());
        $this->assertEquals(2, $query->count());
        $addressId3 = $addressForm->address_id;
        $this->assertEquals($addressId1, $addressId3);
    }
}
