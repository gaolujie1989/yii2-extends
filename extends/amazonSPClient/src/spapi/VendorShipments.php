<?php

namespace lujie\amazon\sp\spapi;

/**
 * Class VendorShipments
 * @package lujie\amazon\sp\spapi
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class VendorShipments extends \DoubleBreak\Spapi\Api\VendorShipments {

    /**
     * Operation GetShipmentDetails
     */
    public function GetShipmentDetails($queryParams = [])
    {
        return $this->send("/vendor/shipping/v1/shipments", [
            'method' => 'GET',
            'query' => $queryParams
        ]);
    }

    /**
     * Operation SubmitShipments
     */
    public function SubmitShipments($body = [])
    {
        return $this->send("/vendor/shipping/v1/shipments", [
            'method' => 'POST',
            'json' => $body
        ]);
    }

    /**
     * Operation GetShipmentLabels
     */
    public function GetShipmentLabels($queryParams = [])
    {
        return $this->send("/vendor/shipping/v1/transportLabels", [
            'method' => 'GET',
            'query' => $queryParams
        ]);
    }
}
