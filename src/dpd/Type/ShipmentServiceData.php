<?php

namespace dpd\Type;

class ShipmentServiceData
{

    /**
     * @var \dpd\Type\GeneralShipmentData
     */
    private $generalShipmentData;

    /**
     * @var \dpd\Type\Parcel
     */
    private $parcels;

    /**
     * @var \dpd\Type\ProductAndServiceData
     */
    private $productAndServiceData;

    /**
     * @return \dpd\Type\GeneralShipmentData
     */
    public function getGeneralShipmentData()
    {
        return $this->generalShipmentData;
    }

    /**
     * @param \dpd\Type\GeneralShipmentData $generalShipmentData
     * @return ShipmentServiceData
     */
    public function withGeneralShipmentData($generalShipmentData)
    {
        $new = clone $this;
        $new->generalShipmentData = $generalShipmentData;

        return $new;
    }

    /**
     * @return \dpd\Type\Parcel
     */
    public function getParcels()
    {
        return $this->parcels;
    }

    /**
     * @param \dpd\Type\Parcel $parcels
     * @return ShipmentServiceData
     */
    public function withParcels($parcels)
    {
        $new = clone $this;
        $new->parcels = $parcels;

        return $new;
    }

    /**
     * @return \dpd\Type\ProductAndServiceData
     */
    public function getProductAndServiceData()
    {
        return $this->productAndServiceData;
    }

    /**
     * @param \dpd\Type\ProductAndServiceData $productAndServiceData
     * @return ShipmentServiceData
     */
    public function withProductAndServiceData($productAndServiceData)
    {
        $new = clone $this;
        $new->productAndServiceData = $productAndServiceData;

        return $new;
    }


}

