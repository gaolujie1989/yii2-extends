<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ShipmentServiceData extends BaseObject
{
    /**
     * @var \lujie\dpd\soap\Type\GeneralShipmentData
     */
    private $generalShipmentData;

    /**
     * @var \lujie\dpd\soap\Type\Parcel
     */
    private $parcels;

    /**
     * @var \lujie\dpd\soap\Type\ProductAndServiceData
     */
    private $productAndServiceData;

    /**
     * @return \lujie\dpd\soap\Type\GeneralShipmentData
     */
    public function getGeneralShipmentData()
    {
        return $this->generalShipmentData;
    }

    /**
     * @param \lujie\dpd\soap\Type\GeneralShipmentData $generalShipmentData
     * @return ShipmentServiceData
     */
    public function withGeneralShipmentData($generalShipmentData)
    {
        $new = clone $this;
        $new->generalShipmentData = $generalShipmentData;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\GeneralShipmentData $generalShipmentData
     * @return $this
     */
    public function setGeneralShipmentData($generalShipmentData) : \lujie\dpd\soap\Type\ShipmentServiceData
    {
        $this->generalShipmentData = $generalShipmentData;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\Parcel
     */
    public function getParcels()
    {
        return $this->parcels;
    }

    /**
     * @param \lujie\dpd\soap\Type\Parcel $parcels
     * @return ShipmentServiceData
     */
    public function withParcels($parcels)
    {
        $new = clone $this;
        $new->parcels = $parcels;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\Parcel $parcels
     * @return $this
     */
    public function setParcels($parcels) : \lujie\dpd\soap\Type\ShipmentServiceData
    {
        $this->parcels = $parcels;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\ProductAndServiceData
     */
    public function getProductAndServiceData()
    {
        return $this->productAndServiceData;
    }

    /**
     * @param \lujie\dpd\soap\Type\ProductAndServiceData $productAndServiceData
     * @return ShipmentServiceData
     */
    public function withProductAndServiceData($productAndServiceData)
    {
        $new = clone $this;
        $new->productAndServiceData = $productAndServiceData;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\ProductAndServiceData $productAndServiceData
     * @return $this
     */
    public function setProductAndServiceData($productAndServiceData) : \lujie\dpd\soap\Type\ShipmentServiceData
    {
        $this->productAndServiceData = $productAndServiceData;
        return $this;
    }
}

