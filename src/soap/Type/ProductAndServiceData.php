<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ProductAndServiceData extends BaseObject
{

    /**
     * @var string
     */
    private $orderType;

    /**
     * @var bool
     */
    private $saturdayDelivery;

    /**
     * @var bool
     */
    private $exWorksDelivery;

    /**
     * @var bool
     */
    private $guarantee;

    /**
     * @var bool
     */
    private $tyres;

    /**
     * @var bool
     */
    private $food;

    /**
     * @var \lujie\dpd\soap\Type\PersonalDelivery
     */
    private $personalDelivery;

    /**
     * @var \lujie\dpd\soap\Type\Pickup
     */
    private $pickup;

    /**
     * @var \lujie\dpd\soap\Type\ParcelShopDelivery
     */
    private $parcelShopDelivery;

    /**
     * @var \lujie\dpd\soap\Type\Notification
     */
    private $predict;

    /**
     * @var \lujie\dpd\soap\Type\Notification
     */
    private $personalDeliveryNotification;

    /**
     * @var \lujie\dpd\soap\Type\ProactiveNotification
     */
    private $proactiveNotification;

    /**
     * @var \lujie\dpd\soap\Type\Delivery
     */
    private $delivery;

    /**
     * @var \lujie\dpd\soap\Type\Address
     */
    private $invoiceAddress;

    /**
     * @var string
     */
    private $countrySpecificService;

    /**
     * @var \lujie\dpd\soap\Type\International
     */
    private $international;

    /**
     * @return string
     */
    public function getOrderType() : string
    {
        return $this->orderType;
    }

    /**
     * @param string $orderType
     * @return $this
     */
    public function setOrderType(string $orderType) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->orderType = $orderType;
        return $this;
    }

    /**
     * @param string $orderType
     * @return ProductAndServiceData
     */
    public function withOrderType(string $orderType) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->orderType = $orderType;

        return $new;
    }

    /**
     * @return bool
     */
    public function getSaturdayDelivery() : bool
    {
        return $this->saturdayDelivery;
    }

    /**
     * @param bool $saturdayDelivery
     * @return $this
     */
    public function setSaturdayDelivery(bool $saturdayDelivery) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->saturdayDelivery = $saturdayDelivery;
        return $this;
    }

    /**
     * @param bool $saturdayDelivery
     * @return ProductAndServiceData
     */
    public function withSaturdayDelivery(bool $saturdayDelivery) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->saturdayDelivery = $saturdayDelivery;

        return $new;
    }

    /**
     * @return bool
     */
    public function getExWorksDelivery() : bool
    {
        return $this->exWorksDelivery;
    }

    /**
     * @param bool $exWorksDelivery
     * @return $this
     */
    public function setExWorksDelivery(bool $exWorksDelivery) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->exWorksDelivery = $exWorksDelivery;
        return $this;
    }

    /**
     * @param bool $exWorksDelivery
     * @return ProductAndServiceData
     */
    public function withExWorksDelivery(bool $exWorksDelivery) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->exWorksDelivery = $exWorksDelivery;

        return $new;
    }

    /**
     * @return bool
     */
    public function getGuarantee() : bool
    {
        return $this->guarantee;
    }

    /**
     * @param bool $guarantee
     * @return $this
     */
    public function setGuarantee(bool $guarantee) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->guarantee = $guarantee;
        return $this;
    }

    /**
     * @param bool $guarantee
     * @return ProductAndServiceData
     */
    public function withGuarantee(bool $guarantee) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->guarantee = $guarantee;

        return $new;
    }

    /**
     * @return bool
     */
    public function getTyres() : bool
    {
        return $this->tyres;
    }

    /**
     * @param bool $tyres
     * @return $this
     */
    public function setTyres(bool $tyres) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->tyres = $tyres;
        return $this;
    }

    /**
     * @param bool $tyres
     * @return ProductAndServiceData
     */
    public function withTyres(bool $tyres) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->tyres = $tyres;

        return $new;
    }

    /**
     * @return bool
     */
    public function getFood() : bool
    {
        return $this->food;
    }

    /**
     * @param bool $food
     * @return $this
     */
    public function setFood(bool $food) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->food = $food;
        return $this;
    }

    /**
     * @param bool $food
     * @return ProductAndServiceData
     */
    public function withFood(bool $food) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->food = $food;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\PersonalDelivery
     */
    public function getPersonalDelivery() : \lujie\dpd\soap\Type\PersonalDelivery
    {
        return $this->personalDelivery;
    }

    /**
     * @param \lujie\dpd\soap\Type\PersonalDelivery $personalDelivery
     * @return $this
     */
    public function setPersonalDelivery($personalDelivery) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->personalDelivery = $personalDelivery;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\PersonalDelivery $personalDelivery
     * @return ProductAndServiceData
     */
    public function withPersonalDelivery(\lujie\dpd\soap\Type\PersonalDelivery $personalDelivery) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->personalDelivery = $personalDelivery;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\Pickup
     */
    public function getPickup() : \lujie\dpd\soap\Type\Pickup
    {
        return $this->pickup;
    }

    /**
     * @param \lujie\dpd\soap\Type\Pickup $pickup
     * @return $this
     */
    public function setPickup($pickup) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->pickup = $pickup;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\Pickup $pickup
     * @return ProductAndServiceData
     */
    public function withPickup(\lujie\dpd\soap\Type\Pickup $pickup) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->pickup = $pickup;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\ParcelShopDelivery
     */
    public function getParcelShopDelivery() : \lujie\dpd\soap\Type\ParcelShopDelivery
    {
        return $this->parcelShopDelivery;
    }

    /**
     * @param \lujie\dpd\soap\Type\ParcelShopDelivery $parcelShopDelivery
     * @return $this
     */
    public function setParcelShopDelivery($parcelShopDelivery) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->parcelShopDelivery = $parcelShopDelivery;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\ParcelShopDelivery $parcelShopDelivery
     * @return ProductAndServiceData
     */
    public function withParcelShopDelivery(\lujie\dpd\soap\Type\ParcelShopDelivery $parcelShopDelivery) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->parcelShopDelivery = $parcelShopDelivery;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\Notification
     */
    public function getPredict() : \lujie\dpd\soap\Type\Notification
    {
        return $this->predict;
    }

    /**
     * @param \lujie\dpd\soap\Type\Notification $predict
     * @return $this
     */
    public function setPredict($predict) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->predict = $predict;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\Notification $predict
     * @return ProductAndServiceData
     */
    public function withPredict(\lujie\dpd\soap\Type\Notification $predict) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->predict = $predict;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\Notification
     */
    public function getPersonalDeliveryNotification() : \lujie\dpd\soap\Type\Notification
    {
        return $this->personalDeliveryNotification;
    }

    /**
     * @param \lujie\dpd\soap\Type\Notification $personalDeliveryNotification
     * @return $this
     */
    public function setPersonalDeliveryNotification($personalDeliveryNotification) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->personalDeliveryNotification = $personalDeliveryNotification;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\Notification $personalDeliveryNotification
     * @return ProductAndServiceData
     */
    public function withPersonalDeliveryNotification(\lujie\dpd\soap\Type\Notification $personalDeliveryNotification) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->personalDeliveryNotification = $personalDeliveryNotification;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\ProactiveNotification
     */
    public function getProactiveNotification() : \lujie\dpd\soap\Type\ProactiveNotification
    {
        return $this->proactiveNotification;
    }

    /**
     * @param \lujie\dpd\soap\Type\ProactiveNotification $proactiveNotification
     * @return $this
     */
    public function setProactiveNotification($proactiveNotification) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->proactiveNotification = $proactiveNotification;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\ProactiveNotification $proactiveNotification
     * @return ProductAndServiceData
     */
    public function withProactiveNotification(\lujie\dpd\soap\Type\ProactiveNotification $proactiveNotification) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->proactiveNotification = $proactiveNotification;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\Delivery
     */
    public function getDelivery() : \lujie\dpd\soap\Type\Delivery
    {
        return $this->delivery;
    }

    /**
     * @param \lujie\dpd\soap\Type\Delivery $delivery
     * @return $this
     */
    public function setDelivery($delivery) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->delivery = $delivery;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\Delivery $delivery
     * @return ProductAndServiceData
     */
    public function withDelivery(\lujie\dpd\soap\Type\Delivery $delivery) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->delivery = $delivery;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\Address
     */
    public function getInvoiceAddress() : \lujie\dpd\soap\Type\Address
    {
        return $this->invoiceAddress;
    }

    /**
     * @param \lujie\dpd\soap\Type\Address $invoiceAddress
     * @return $this
     */
    public function setInvoiceAddress($invoiceAddress) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->invoiceAddress = $invoiceAddress;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\Address $invoiceAddress
     * @return ProductAndServiceData
     */
    public function withInvoiceAddress(\lujie\dpd\soap\Type\Address $invoiceAddress) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->invoiceAddress = $invoiceAddress;

        return $new;
    }

    /**
     * @return string
     */
    public function getCountrySpecificService() : string
    {
        return $this->countrySpecificService;
    }

    /**
     * @param string $countrySpecificService
     * @return $this
     */
    public function setCountrySpecificService(string $countrySpecificService) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->countrySpecificService = $countrySpecificService;
        return $this;
    }

    /**
     * @param string $countrySpecificService
     * @return ProductAndServiceData
     */
    public function withCountrySpecificService(string $countrySpecificService) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->countrySpecificService = $countrySpecificService;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\International
     */
    public function getInternational() : \lujie\dpd\soap\Type\International
    {
        return $this->international;
    }

    /**
     * @param \lujie\dpd\soap\Type\International $international
     * @return $this
     */
    public function setInternational($international) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $this->international = $international;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\International $international
     * @return ProductAndServiceData
     */
    public function withInternational(\lujie\dpd\soap\Type\International $international) : \lujie\dpd\soap\Type\ProductAndServiceData
    {
        $new = clone $this;
        $new->international = $international;

        return $new;
    }


}

