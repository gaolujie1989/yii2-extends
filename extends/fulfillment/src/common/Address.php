<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\common;

use yii\base\Model;

/**
 * Class ItemBarcode
 * @package lujie\fulfillment\common
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
    public $houseNo;

    /**
     * @var string
     */
    public $additional;

    /**
     * @var string
     */
    public $postalCode;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $phone;
}
