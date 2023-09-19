<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class Address extends BaseObject
{
    /**
     * @var string
     */
    private $name1;

    /**
     * @var string
     */
    private $name2;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $houseNo;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var int
     */
    private $gln;

    /**
     * @var string
     */
    private $customerNumber;

    /**
     * @var string
     */
    private $contact;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $mobile;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var string
     */
    private $iaccount;

    /**
     * @return string
     */
    public function getName1()
    {
        return $this->name1;
    }

    /**
     * @param string $name1
     * @return Address
     */
    public function withName1($name1)
    {
        $new = clone $this;
        $new->name1 = $name1;

        return $new;
    }

    /**
     * @param string $name1
     * @return $this
     */
    public function setName1(string $name1) : \lujie\dpd\soap\Type\Address
    {
        $this->name1 = $name1;
        return $this;
    }

    /**
     * @return string
     */
    public function getName2()
    {
        return $this->name2;
    }

    /**
     * @param string $name2
     * @return Address
     */
    public function withName2($name2)
    {
        $new = clone $this;
        $new->name2 = $name2;

        return $new;
    }

    /**
     * @param string $name2
     * @return $this
     */
    public function setName2(string $name2) : \lujie\dpd\soap\Type\Address
    {
        $this->name2 = $name2;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return Address
     */
    public function withStreet($street)
    {
        $new = clone $this;
        $new->street = $street;

        return $new;
    }

    /**
     * @param string $street
     * @return $this
     */
    public function setStreet(string $street) : \lujie\dpd\soap\Type\Address
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getHouseNo()
    {
        return $this->houseNo;
    }

    /**
     * @param string $houseNo
     * @return Address
     */
    public function withHouseNo($houseNo)
    {
        $new = clone $this;
        $new->houseNo = $houseNo;

        return $new;
    }

    /**
     * @param string $houseNo
     * @return $this
     */
    public function setHouseNo(string $houseNo) : \lujie\dpd\soap\Type\Address
    {
        $this->houseNo = $houseNo;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return Address
     */
    public function withState($state)
    {
        $new = clone $this;
        $new->state = $state;

        return $new;
    }

    /**
     * @param string $state
     * @return $this
     */
    public function setState(string $state) : \lujie\dpd\soap\Type\Address
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Address
     */
    public function withCountry($country)
    {
        $new = clone $this;
        $new->country = $country;

        return $new;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country) : \lujie\dpd\soap\Type\Address
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return Address
     */
    public function withZipCode($zipCode)
    {
        $new = clone $this;
        $new->zipCode = $zipCode;

        return $new;
    }

    /**
     * @param string $zipCode
     * @return $this
     */
    public function setZipCode(string $zipCode) : \lujie\dpd\soap\Type\Address
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Address
     */
    public function withCity($city)
    {
        $new = clone $this;
        $new->city = $city;

        return $new;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city) : \lujie\dpd\soap\Type\Address
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return int
     */
    public function getGln()
    {
        return $this->gln;
    }

    /**
     * @param int $gln
     * @return Address
     */
    public function withGln($gln)
    {
        $new = clone $this;
        $new->gln = $gln;

        return $new;
    }

    /**
     * @param int $gln
     * @return $this
     */
    public function setGln(int $gln) : \lujie\dpd\soap\Type\Address
    {
        $this->gln = $gln;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerNumber()
    {
        return $this->customerNumber;
    }

    /**
     * @param string $customerNumber
     * @return Address
     */
    public function withCustomerNumber($customerNumber)
    {
        $new = clone $this;
        $new->customerNumber = $customerNumber;

        return $new;
    }

    /**
     * @param string $customerNumber
     * @return $this
     */
    public function setCustomerNumber(string $customerNumber) : \lujie\dpd\soap\Type\Address
    {
        $this->customerNumber = $customerNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param string $contact
     * @return Address
     */
    public function withContact($contact)
    {
        $new = clone $this;
        $new->contact = $contact;

        return $new;
    }

    /**
     * @param string $contact
     * @return $this
     */
    public function setContact(string $contact) : \lujie\dpd\soap\Type\Address
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Address
     */
    public function withPhone($phone)
    {
        $new = clone $this;
        $new->phone = $phone;

        return $new;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone(string $phone) : \lujie\dpd\soap\Type\Address
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     * @return Address
     */
    public function withMobile($mobile)
    {
        $new = clone $this;
        $new->mobile = $mobile;

        return $new;
    }

    /**
     * @param string $mobile
     * @return $this
     */
    public function setMobile(string $mobile) : \lujie\dpd\soap\Type\Address
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     * @return Address
     */
    public function withFax($fax)
    {
        $new = clone $this;
        $new->fax = $fax;

        return $new;
    }

    /**
     * @param string $fax
     * @return $this
     */
    public function setFax(string $fax) : \lujie\dpd\soap\Type\Address
    {
        $this->fax = $fax;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Address
     */
    public function withEmail($email)
    {
        $new = clone $this;
        $new->email = $email;

        return $new;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email) : \lujie\dpd\soap\Type\Address
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return Address
     */
    public function withComment($comment)
    {
        $new = clone $this;
        $new->comment = $comment;

        return $new;
    }

    /**
     * @param string $comment
     * @return $this
     */
    public function setComment(string $comment) : \lujie\dpd\soap\Type\Address
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getIaccount()
    {
        return $this->iaccount;
    }

    /**
     * @param string $iaccount
     * @return Address
     */
    public function withIaccount($iaccount)
    {
        $new = clone $this;
        $new->iaccount = $iaccount;

        return $new;
    }

    /**
     * @param string $iaccount
     * @return $this
     */
    public function setIaccount(string $iaccount) : \lujie\dpd\soap\Type\Address
    {
        $this->iaccount = $iaccount;
        return $this;
    }
}

