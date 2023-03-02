<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\models;

/**
 * Interface AddressInterface
 * @package lujie\extend\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
interface AddressInterface
{
    /**
     * @return string
     */
    public function getCountry(): string;

    /**
     * @return string
     */
    public function getState(): string;

    /**
     * @return string
     */
    public function getCity(): string;

    /**
     * @return string
     */
    public function getPostalCode(): string;

    /**
     * @return string
     */
    public function getCompanyName(): string;

    /**
     * @return string
     */
    public function getFirstName(): string;

    /**
     * @return string
     */
    public function getLastName(): string;

    /**
     * @return string
     */
    public function getStreet(): string;

    /**
     * @return string
     */
    public function getStreetNo(): string;

    /**
     * @return string
     */
    public function getAdditional(): string;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getPhone(): string;
}