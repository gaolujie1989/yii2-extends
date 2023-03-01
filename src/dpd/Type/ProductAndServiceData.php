<?php

namespace dpd\Type;

class ProductAndServiceData
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
     * @var \dpd\Type\PersonalDelivery
     */
    private $personalDelivery;

    /**
     * @var \dpd\Type\Pickup
     */
    private $pickup;

    /**
     * @var \dpd\Type\ParcelShopDelivery
     */
    private $parcelShopDelivery;

    /**
     * @var \dpd\Type\Notification
     */
    private $predict;

    /**
     * @var \dpd\Type\Notification
     */
    private $personalDeliveryNotification;

    /**
     * @var \dpd\Type\ProactiveNotification
     */
    private $proactiveNotification;

    /**
     * @var \dpd\Type\Delivery
     */
    private $delivery;

    /**
     * @var \dpd\Type\Address
     */
    private $invoiceAddress;

    /**
     * @var string
     */
    private $countrySpecificService;

    /**
     * @var \dpd\Type\International
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
     * @return \dpd\Type\PersonalDelivery
     */
    public function getPersonalDelivery()
    {
        return $this->personalDelivery;
    }

    /**
     * @param \dpd\Type\PersonalDelivery $personalDelivery
     * @return ProductAndServiceData
     */
    public function withPersonalDelivery($personalDelivery)
    {
        $new = clone $this;
        $new->personalDelivery = $personalDelivery;

        return $new;
    }

    /**
     * @return \dpd\Type\Pickup
     */
    public function getPickup()
    {
        return $this->pickup;
    }

    /**
     * @param \dpd\Type\Pickup $pickup
     * @return ProductAndServiceData
     */
    public function withPickup($pickup)
    {
        $new = clone $this;
        $new->pickup = $pickup;

        return $new;
    }

    /**
     * @return \dpd\Type\ParcelShopDelivery
     */
    public function getParcelShopDelivery()
    {
        return $this->parcelShopDelivery;
    }

    /**
     * @param \dpd\Type\ParcelShopDelivery $parcelShopDelivery
     * @return ProductAndServiceData
     */
    public function withParcelShopDelivery($parcelShopDelivery)
    {
        $new = clone $this;
        $new->parcelShopDelivery = $parcelShopDelivery;

        return $new;
    }

    /**
     * @return \dpd\Type\Notification
     */
    public function getPredict()
    {
        return $this->predict;
    }

    /**
     * @param \dpd\Type\Notification $predict
     * @return ProductAndServiceData
     */
    public function withPredict($predict)
    {
        $new = clone $this;
        $new->predict = $predict;

        return $new;
    }

    /**
     * @return \dpd\Type\Notification
     */
    public function getPersonalDeliveryNotification()
    {
        return $this->personalDeliveryNotification;
    }

    /**
     * @param \dpd\Type\Notification $personalDeliveryNotification
     * @return ProductAndServiceData
     */
    public function withPersonalDeliveryNotification($personalDeliveryNotification)
    {
        $new = clone $this;
        $new->personalDeliveryNotification = $personalDeliveryNotification;

        return $new;
    }

    /**
     * @return \dpd\Type\ProactiveNotification
     */
    public function getProactiveNotification()
    {
        return $this->proactiveNotification;
    }

    /**
     * @param \dpd\Type\ProactiveNotification $proactiveNotification
     * @return ProductAndServiceData
     */
    public function withProactiveNotification($proactiveNotification)
    {
        $new = clone $this;
        $new->proactiveNotification = $proactiveNotification;

        return $new;
    }

    /**
     * @return \dpd\Type\Delivery
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param \dpd\Type\Delivery $delivery
     * @return ProductAndServiceData
     */
    public function withDelivery($delivery)
    {
        $new = clone $this;
        $new->delivery = $delivery;

        return $new;
    }

    /**
     * @return \dpd\Type\Address
     */
    public function getInvoiceAddress()
    {
        return $this->invoiceAddress;
    }

    /**
     * @param \dpd\Type\Address $invoiceAddress
     * @return ProductAndServiceData
     */
    public function withInvoiceAddress($invoiceAddress)
    {
        $new = clone $this;
        $new->invoiceAddress = $invoiceAddress;

        return $new;
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
     * @return \dpd\Type\International
     */
    public function getInternational()
    {
        return $this->international;
    }

    /**
     * @param \dpd\Type\International $international
     * @return ProductAndServiceData
     */
    public function withInternational($international)
    {
        $new = clone $this;
        $new->international = $international;

        return $new;
    }


}

