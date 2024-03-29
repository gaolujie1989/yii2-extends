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
    public function getOrderType()
    {
        return $this->orderType;
    }

    /**
     * @param string $orderType
     * @return ProductAndServiceData
     */
    public function withOrderType($orderType)
    {
        $new = clone $this;
        $new->orderType = $orderType;

        return $new;
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
     * @return bool
     */
    public function getSaturdayDelivery()
    {
        return $this->saturdayDelivery;
    }

    /**
     * @param bool $saturdayDelivery
     * @return ProductAndServiceData
     */
    public function withSaturdayDelivery($saturdayDelivery)
    {
        $new = clone $this;
        $new->saturdayDelivery = $saturdayDelivery;

        return $new;
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
     * @return bool
     */
    public function getExWorksDelivery()
    {
        return $this->exWorksDelivery;
    }

    /**
     * @param bool $exWorksDelivery
     * @return ProductAndServiceData
     */
    public function withExWorksDelivery($exWorksDelivery)
    {
        $new = clone $this;
        $new->exWorksDelivery = $exWorksDelivery;

        return $new;
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
     * @return bool
     */
    public function getGuarantee()
    {
        return $this->guarantee;
    }

    /**
     * @param bool $guarantee
     * @return ProductAndServiceData
     */
    public function withGuarantee($guarantee)
    {
        $new = clone $this;
        $new->guarantee = $guarantee;

        return $new;
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
     * @return bool
     */
    public function getTyres()
    {
        return $this->tyres;
    }

    /**
     * @param bool $tyres
     * @return ProductAndServiceData
     */
    public function withTyres($tyres)
    {
        $new = clone $this;
        $new->tyres = $tyres;

        return $new;
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
     * @return bool
     */
    public function getFood()
    {
        return $this->food;
    }

    /**
     * @param bool $food
     * @return ProductAndServiceData
     */
    public function withFood($food)
    {
        $new = clone $this;
        $new->food = $food;

        return $new;
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
     * @return \lujie\dpd\soap\Type\PersonalDelivery
     */
    public function getPersonalDelivery()
    {
        return $this->personalDelivery;
    }

    /**
     * @param \lujie\dpd\soap\Type\PersonalDelivery $personalDelivery
     * @return ProductAndServiceData
     */
    public function withPersonalDelivery($personalDelivery)
    {
        $new = clone $this;
        $new->personalDelivery = $personalDelivery;

        return $new;
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
     * @return \lujie\dpd\soap\Type\Pickup
     */
    public function getPickup()
    {
        return $this->pickup;
    }

    /**
     * @param \lujie\dpd\soap\Type\Pickup $pickup
     * @return ProductAndServiceData
     */
    public function withPickup($pickup)
    {
        $new = clone $this;
        $new->pickup = $pickup;

        return $new;
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
     * @return \lujie\dpd\soap\Type\ParcelShopDelivery
     */
    public function getParcelShopDelivery()
    {
        return $this->parcelShopDelivery;
    }

    /**
     * @param \lujie\dpd\soap\Type\ParcelShopDelivery $parcelShopDelivery
     * @return ProductAndServiceData
     */
    public function withParcelShopDelivery($parcelShopDelivery)
    {
        $new = clone $this;
        $new->parcelShopDelivery = $parcelShopDelivery;

        return $new;
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
     * @return \lujie\dpd\soap\Type\Notification
     */
    public function getPredict()
    {
        return $this->predict;
    }

    /**
     * @param \lujie\dpd\soap\Type\Notification $predict
     * @return ProductAndServiceData
     */
    public function withPredict($predict)
    {
        $new = clone $this;
        $new->predict = $predict;

        return $new;
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
     * @return \lujie\dpd\soap\Type\Notification
     */
    public function getPersonalDeliveryNotification()
    {
        return $this->personalDeliveryNotification;
    }

    /**
     * @param \lujie\dpd\soap\Type\Notification $personalDeliveryNotification
     * @return ProductAndServiceData
     */
    public function withPersonalDeliveryNotification($personalDeliveryNotification)
    {
        $new = clone $this;
        $new->personalDeliveryNotification = $personalDeliveryNotification;

        return $new;
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
     * @return \lujie\dpd\soap\Type\ProactiveNotification
     */
    public function getProactiveNotification()
    {
        return $this->proactiveNotification;
    }

    /**
     * @param \lujie\dpd\soap\Type\ProactiveNotification $proactiveNotification
     * @return ProductAndServiceData
     */
    public function withProactiveNotification($proactiveNotification)
    {
        $new = clone $this;
        $new->proactiveNotification = $proactiveNotification;

        return $new;
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
     * @return \lujie\dpd\soap\Type\Delivery
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param \lujie\dpd\soap\Type\Delivery $delivery
     * @return ProductAndServiceData
     */
    public function withDelivery($delivery)
    {
        $new = clone $this;
        $new->delivery = $delivery;

        return $new;
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
     * @return \lujie\dpd\soap\Type\Address
     */
    public function getInvoiceAddress()
    {
        return $this->invoiceAddress;
    }

    /**
     * @param \lujie\dpd\soap\Type\Address $invoiceAddress
     * @return ProductAndServiceData
     */
    public function withInvoiceAddress($invoiceAddress)
    {
        $new = clone $this;
        $new->invoiceAddress = $invoiceAddress;

        return $new;
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
     * @return string
     */
    public function getCountrySpecificService()
    {
        return $this->countrySpecificService;
    }

    /**
     * @param string $countrySpecificService
     * @return ProductAndServiceData
     */
    public function withCountrySpecificService($countrySpecificService)
    {
        $new = clone $this;
        $new->countrySpecificService = $countrySpecificService;

        return $new;
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
     * @return \lujie\dpd\soap\Type\International
     */
    public function getInternational()
    {
        return $this->international;
    }

    /**
     * @param \lujie\dpd\soap\Type\International $international
     * @return ProductAndServiceData
     */
    public function withInternational($international)
    {
        $new = clone $this;
        $new->international = $international;

        return $new;
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
}

