<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\constants;

class SalesChannelConst
{
    public const ACCOUNT_TYPE_PM = 'PM';
    public const ACCOUNT_TYPE_AMAZON = 'AMAZON';
    public const ACCOUNT_TYPE_EBAY = 'EBAY';
    public const ACCOUNT_TYPE_SHOPIFY = 'SHOPIFY';
    public const ACCOUNT_TYPE_OTTO = 'OTTO';

    public const CHANNEL_STATUS_WAIT_PAYMENT = 0;
    public const CHANNEL_STATUS_PAID = 10;
    public const CHANNEL_STATUS_PENDING = 20;
    public const CHANNEL_STATUS_SHIPPED = 100;
    public const CHANNEL_STATUS_CANCELLED = 110;
    public const CHANNEL_STATUS_TO_SHIPPED = 210;
    public const CHANNEL_STATUS_TO_CANCELLED = 220;

    public const ITEM_PUSH_PART_ALL = 'ALL';
    public const ITEM_PUSH_PART_INFO = 'INFO';
    public const ITEM_PUSH_PART_DESC = 'DESC';
    public const ITEM_PUSH_PART_IMAGE = 'IMAGE';
    public const ITEM_PUSH_PART_PRICE = 'PRICE';
    public const ITEM_PUSH_PART_STOCK = 'STOCK';
}
