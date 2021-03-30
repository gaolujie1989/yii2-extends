<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class Address implements RequestInterface
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
     * Constructor
     *
     * @var string $name1
     * @var string $name2
     * @var string $street
     * @var string $houseNo
     * @var string $state
     * @var string $country
     * @var string $zipCode
     * @var string $city
     * @var int $gln
     * @var string $customerNumber
     * @var string $contact
     * @var string $phone
     * @var string $fax
     * @var string $email
     * @var string $comment
     * @var string $iaccount
     */
    public function __construct($name1, $name2, $street, $houseNo, $state, $country, $zipCode, $city, $gln, $customerNumber, $contact, $phone, $fax, $email, $comment, $iaccount)
    {
        $this->name1 = $name1;
        $this->name2 = $name2;
        $this->street = $street;
        $this->houseNo = $houseNo;
        $this->state = $state;
        $this->country = $country;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->gln = $gln;
        $this->customerNumber = $customerNumber;
        $this->contact = $contact;
        $this->phone = $phone;
        $this->fax = $fax;
        $this->email = $email;
        $this->comment = $comment;
        $this->iaccount = $iaccount;
    }

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
}
