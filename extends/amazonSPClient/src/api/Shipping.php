<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description Provides programmatic access to Amazon Shipping APIs.

 **Note:** If you are new to the Amazon Shipping API, refer to the latest version of <a href="https://developer-docs.amazon.com/amazon-shipping/docs/shipping-api-v2-reference">Amazon Shipping API (v2)</a> on the <a href="https://developer-docs.amazon.com/amazon-shipping/">Amazon Shipping Developer Documentation</a> site.
*/
class Shipping extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Create a new shipment.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 5 | 15 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag shipping
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The payload for createShipment operation
     *      - *errors* - 
     *          - Encountered errors for the operation.
     */
    public function createShipment(array $data): array
    {
        return $this->api("/shipping/v1/shipments", 'POST', $data);
    }
                    
    /**
     * @description Return the entire shipment object for the shipmentId.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 5 | 15 |

For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag shipping
     * @param string $shipmentId 
     * @return array
     *      - *payload* - 
     *          - The payload for getShipment operation
     *      - *errors* - 
     *          - Encountered errors for the operation.
     */
    public function getShipment(string $shipmentId): array
    {
        return $this->api("/shipping/v1/shipments/{$shipmentId}");
    }
                    
    /**
     * @description Cancel a shipment by the given shipmentId.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 5 | 15 |

For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag shipping
     * @param string $shipmentId 
     * @return array
     *      - *errors* - 
     *          - Encountered errors for the operation.
     */
    public function cancelShipment(string $shipmentId): array
    {
        return $this->api("/shipping/v1/shipments/{$shipmentId}/cancel", 'POST');
    }
                    
    /**
     * @description Purchase shipping labels based on a given rate.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 5 | 15 |

For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag shipping
     * @param string $shipmentId 
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The payload for purchaseLabels operation
     *      - *errors* - 
     *          - Encountered errors for the operation.
     */
    public function purchaseLabels(string $shipmentId, array $data): array
    {
        return $this->api("/shipping/v1/shipments/{$shipmentId}/purchaseLabels", 'POST', $data);
    }
                    
    /**
     * @description Retrieve shipping label based on the shipment id and tracking id.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 5 | 15 |

For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag shipping
     * @param string $shipmentId 
     * @param string $trackingId 
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The payload for retrieveShippingLabel operation
     *      - *errors* - 
     *          - Encountered errors for the operation.
     */
    public function retrieveShippingLabel(string $shipmentId, string $trackingId, array $data): array
    {
        return $this->api("/shipping/v1/shipments/{$shipmentId}/containers/{$trackingId}/label", 'POST', $data);
    }
                    
    /**
     * @description Purchase shipping labels.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 5 | 15 |

For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag shipping
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The payload for purchaseShipment operation
     *      - *errors* - 
     *          - Encountered errors for the operation.
     */
    public function purchaseShipment(array $data): array
    {
        return $this->api("/shipping/v1/purchaseShipment", 'POST', $data);
    }
                    
    /**
     * @description Get service rates.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 5 | 15 |

For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag shipping
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The payload for getRates operation
     *      - *errors* - 
     *          - Encountered errors for the operation.
     */
    public function getRates(array $data): array
    {
        return $this->api("/shipping/v1/rates", 'POST', $data);
    }
                    
    /**
     * @description Verify if the current account is valid.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 5 | 15 |

For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag shipping
     * @return array
     *      - *payload* - 
     *          - The payload for getAccount operation
     *      - *errors* - 
     *          - Encountered errors for the operation.
     */
    public function getAccount(): array
    {
        return $this->api("/shipping/v1/account");
    }
                    
    /**
     * @description Return the tracking information of a shipment.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 1 |

For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag shipping
     * @param string $trackingId 
     * @return array
     *      - *payload* - 
     *          - The payload for getTrackingInformation operation
     *      - *errors* - 
     *          - Encountered errors for the operation.
     */
    public function getTrackingInformation(string $trackingId): array
    {
        return $this->api("/shipping/v1/tracking/{$trackingId}");
    }
    
}
