<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\sales\channel\constants\SalesChannelConst;

return [
    'salesChannelAccountType' => [
        'PM' => SalesChannelConst::ACCOUNT_TYPE_PM,
    ],
    'salesChannelStatus' => [
        'WAIT_PAYMENT' => SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT,
        'PAID' => SalesChannelConst::CHANNEL_STATUS_PAID,
        'PENDING' => SalesChannelConst::CHANNEL_STATUS_PENDING,
        'SHIPPED' => SalesChannelConst::CHANNEL_STATUS_SHIPPED,
        'CANCELLED' => SalesChannelConst::CHANNEL_STATUS_CANCELLED,
        'TO_SHIPPED' => SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED,
        'TO_CANCELLED' => SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED,
    ],
];