<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\sales\channel\constants\SalesChannelConst;

return [
    'salesChannelAccountType' => [
        SalesChannelConst::ACCOUNT_TYPE_PM => 'PM',
    ],
    'salesChannelStatus' => [
        SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT => 'WAIT_PAYMENT',
        SalesChannelConst::CHANNEL_STATUS_PAID => 'PAID',
        SalesChannelConst::CHANNEL_STATUS_PENDING => 'PENDING',
        SalesChannelConst::CHANNEL_STATUS_SHIPPED => 'SHIPPED',
        SalesChannelConst::CHANNEL_STATUS_CANCELLED => 'CANCELLED',
        SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED => 'TO_SHIPPED',
        SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED => 'TO_CANCELLED',
    ],
];