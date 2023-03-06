<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\models;


use yii\base\Model;

/**
 * Class Address
 * @package lujie\extend\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Address extends Model implements AddressInterface
{
    /**
     * @var int
     */
    public $addressId;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $state;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $postalCode;

    /**
     * @var string
     */
    public $companyName;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $street;

    /**
     * @var string
     */
    public $streetNo;

    /**
     * @var string
     */
    public $additional;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $phone;

    #region AddressInterface

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country ?: '';
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state ?: '';
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city ?: '';
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode ?: '';
    }

    /**
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName ?: '';
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName ?: '';
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName ?: '';
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street ?: '';
    }

    /**
     * @return string
     */
    public function getStreetNo(): string
    {
        return $this->streetNo ?: '';
    }

    /**
     * @return string
     */
    public function getAdditional(): string
    {
        return $this->additional ?: '';
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email ?: '';
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone ?: '';
    }

    #endregion
}