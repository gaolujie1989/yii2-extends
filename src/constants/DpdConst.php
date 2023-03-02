<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\dpd\constants;

class DpdConst
{
    public const PRODUCT_DPD_CLASSIC = 'CL';
    public const PRODUCT_DPD_0830 = 'E830';
    public const PRODUCT_DPD_1000 = 'E10';
    public const PRODUCT_DPD_1200 = 'E12';
    public const PRODUCT_DPD_1800 = 'E18';
    public const PRODUCT_DPD_EXPRESS = 'IE2';
    public const PRODUCT_DPD_INTERNATIONAL_MAIL = 'MAIL';
    public const PRODUCT_DPD_MAX = 'MAX';
    public const PRODUCT_DPD_PARCEL_LETTER = 'PL';
    public const PRODUCT_DPD_PRIORITY = 'PM4';

    public const ORDER_TYPE_CONSIGNMENT = 'consignment';
    public const ORDER_TYPE_COLLECTION = 'collection request order';
    public const ORDER_TYPE_PICKUP = 'pickup information';
}