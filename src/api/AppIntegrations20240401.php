<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description With the AppIntegrations API v2024-04-01, you can send notifications to Amazon Selling Partners and display the notifications in Seller Central.
*/
class AppIntegrations20240401 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Create a notification for sellers in Seller Central.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 5 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The preceding table indicates the default rate and burst values for this operation. Sellers whose business demands require higher throughput may have higher rate and burst values than those shown here. For more information, refer to [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag appIntegrations
     * @param array $data 
     * @return array
     *      - *notificationId* - string
     *          - The unique identifier assigned to each notification.
     */
    public function createNotification(array $data): array
    {
        return $this->api("/appIntegrations/2024-04-01/notifications", 'POST', $data);
    }
                    
    /**
     * @description Remove your application's notifications from the Appstore notifications dashboard.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 5 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The preceding table indicates the default rate and burst values for this operation. Sellers whose business demands require higher throughput may have higher rate and burst values than those shown here. For more information, refer to [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag appIntegrations
     * @param array $data 
     */
    public function deleteNotifications(array $data): void
    {
        $this->api("/appIntegrations/2024-04-01/notifications/deletion", 'POST', $data);
    }
                    
    /**
     * @description Records the seller's response to a notification.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 5 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The preceding table indicates the default rate and burst values for this operation. Sellers whose business demands require higher throughput may have higher rate and burst values than those shown here. For more information, refer to [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag appIntegrations
     * @param string $notificationId A `notificationId` uniquely identifies a notification.
     * @param array $data 
     */
    public function recordActionFeedback(string $notificationId, array $data): void
    {
        $this->api("/appIntegrations/2024-04-01/notifications/{$notificationId}/feedback", 'POST', $data);
    }
    
}
