<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\constants;

class SalesChannelConst
{
    public const ACCOUNT_TYPE_PM = 'PM';

    public const CHANNEL_STATUS_WAIT_PAYMENT = 0;
    public const CHANNEL_STATUS_PAID = 10;
    public const CHANNEL_STATUS_PENDING = 20;
    public const CHANNEL_STATUS_SHIPPED = 100;
    public const CHANNEL_STATUS_CANCELLED = 110;
    public const CHANNEL_STATUS_TO_SHIPPED = 210;
    public const CHANNEL_STATUS_TO_CANCELLED = 220;
}
