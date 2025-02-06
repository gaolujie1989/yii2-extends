<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\amazon\advertising\constants;

use lujie\amazon\advertising\api\SponsoredBrandsV4Const;
use lujie\amazon\advertising\api\SponsoredProductsV3Const;

class AmazonAdvertisingCommonModelConst
{
    public const AD_PRODUCT_SPONSORED_PRODUCTS = 'SPONSORED_PRODUCTS';
    public const AD_PRODUCT_SPONSORED_BRANDS = 'SPONSORED_BRANDS';
    public const AD_PRODUCT_SPONSORED_DISPLAY = 'SPONSORED_DISPLAY';

    public const AD_TYPE_PRODUCT_AD = 'PRODUCT_AD';
    public const AD_TYPE_IMAGE = 'IMAGE';
    public const AD_TYPE_VIDEO = 'VIDEO';
    public const AD_TYPE_PRODUCT_COLLECTION = 'PRODUCT_COLLECTION';
    public const AD_TYPE_STORE_SPOTLIGHT = 'STORE_SPOTLIGHT';

    public const BID_STRATEGY_SALES_DOWN_ONLY = 'SALES_DOWN_ONLY';
    public const BID_STRATEGY_SALES = 'SALES';
    public const BID_STRATEGY_NEW_TO_BRAND = 'NEW_TO_BRAND';
    public const BID_STRATEGY_RULE_BASED = 'RULE_BASED';
    public const BID_STRATEGY_NONE = 'NONE';

    public const SPONSORED_PRODUCTS_BIDDING_STRATEGY = [
        self::BID_STRATEGY_SALES_DOWN_ONLY => SponsoredProductsV3Const::BIDDING_STRATEGY_LEGACY_FOR_SALES,
        self::BID_STRATEGY_SALES => SponsoredProductsV3Const::BIDDING_STRATEGY_AUTO_FOR_SALES,
        self::BID_STRATEGY_NEW_TO_BRAND => null,
        self::BID_STRATEGY_RULE_BASED => SponsoredProductsV3Const::BIDDING_STRATEGY_RULE_BASED,
        self::BID_STRATEGY_NONE => SponsoredProductsV3Const::BIDDING_STRATEGY_MANUAL,
    ];

    public const SPONSORED_BRANDS_BID_OPTIMIZATION_STRATEGY = [
        self::BID_STRATEGY_SALES_DOWN_ONLY => null,
        self::BID_STRATEGY_SALES => SponsoredBrandsV4Const::BID_OPTIMIZATION_STRATEGY_MAXIMIZE_IMMEDIATE_SALES,
        self::BID_STRATEGY_NEW_TO_BRAND => SponsoredBrandsV4Const::BID_OPTIMIZATION_STRATEGY_MAXIMIZE_NEW_TO_BRAND_CUSTOMERS,
        self::BID_STRATEGY_RULE_BASED => null,
        self::BID_STRATEGY_NONE => null,
    ];

    public const BUDGET_TYPE_MONETARY = 'MONETARY';
}
