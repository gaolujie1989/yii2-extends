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
class Address extends Model
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
}