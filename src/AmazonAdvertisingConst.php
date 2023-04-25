<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising;

class AmazonAdvertisingConst
{
    public const API_URL_NA = 'https://advertising-api.amazon.com';
    public const API_URL_EU = 'https://advertising-api-eu.amazon.com';
    public const API_URL_FE = 'https://advertising-api-fe.amazon.com';

    public const AUTH_URL_NA = 'https://www.amazon.com/ap/oa';
    public const AUTH_URL_EU = 'https://eu.account.amazon.com/ap/oa';
    public const AUTH_URL_FE = 'https://apac.account.amazon.com/ap/oa';

    public const TOKEN_URL_NA = 'https://api.amazon.com/auth/o2/token';
    public const TOKEN_URL_EU = 'https://api.amazon.co.uk/auth/o2/token';
    public const TOKEN_URL_FE = 'https://api.amazon.co.jp/auth/o2/token';

    public const SCOPE_DSP = 'advertising::campaign_management';
    public const SCOPE_DATA_PROVIDER = 'advertising::audiences';

    public const V2REPORT_STATUS_IN_PROGRESS = 'IN_PROGRESS';
    public const V2REPORT_STATUS_SUCCESS = 'SUCCESS';
    public const V2REPORT_STATUS_FAILURE = 'FAILURE';
    public const V3REPORT_STATUS_PENDING = 'PENDING';
    public const V3REPORT_STATUS_PROCESSING = 'PROCESSING';
    public const V3REPORT_STATUS_COMPLETED = 'COMPLETED';
    public const V3REPORT_STATUS_FAILED = 'FAILED';

    public const AD_TYPE_PRODUCT = 'sp';
    public const AD_TYPE_BRAND = 'sb';
    public const AD_TYPE_DISPLAY = 'sd';

    public const CAMPAIGN_TYPE_PRODUCT = 'sponsoredProducts';
    public const CAMPAIGN_TYPE_BRAND = 'sponsoredBrands';
    public const CAMPAIGN_TYPE_DISPLAY = 'sponsoredDisplay';

    public const AD_PRODUCT_PRODUCT = 'SPONSORED_PRODUCTS';

    public const RECORD_TYPE_CAMPAIGN = 'campaigns';
    public const RECORD_TYPE_AD_GROUP = 'adGroups';
    public const RECORD_TYPE_PRODUCT_AD = 'productAds';
    public const RECORD_TYPE_TARGET = 'targets';
    public const RECORD_TYPE_KEYWORD = 'keywords';
    public const RECORD_TYPE_ASIN = 'asins';
    public const RECORD_TYPE_AD = 'ads';
    public const RECORD_TYPE_NEGATIVE_KEYWORD = 'negativeKeywords';
    public const RECORD_TYPE_CAMPAIGN_NEGATIVE_KEYWORD = 'campaignNegativeKeywords';
    public const RECORD_TYPE_NEGATIVE_TARGET = 'negativeTargets';

    public const PLACEMENT_SEARCH = 'SEARCH';
    public const PLACEMENT_DETAIL = 'DETAIL';
    public const PLACEMENT_OTHER = 'OTHER';

    public const CAMPAIGN_PLACEMENTS = [
        'Top of Search on-Amazon' => self::PLACEMENT_SEARCH,
        'Detail Page on-Amazon' => self::PLACEMENT_DETAIL,
        'Other on-Amazon' => self::PLACEMENT_OTHER,
    ];

    public const REPORT_TYPES = [
        'V2ProductCampaign' => [
            'deprecated' => true,
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,currency,impressions',
        ],
        'V2BrandCampaign' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,dpv14d,impressions,unitsSold14d',
        ],
        'V2BrandVideoCampaign' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,dpv14d,impressions,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d',
            'creativeType' => 'video'
        ],
        'V2DisplayCampaign' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignBudget,campaignId,campaignName,campaignStatus,clicks,cost,costType,currency,impressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewImpressions,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d',
            'tactic' => 'T00020',
        ],

        'V2ProductCampaignPlacement' => [
            'deprecated' => true,
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,bidPlus,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,currency,impressions',
            'segment' => 'placement',
        ],
        'V2BrandCampaignPlacement' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,dpv14d,impressions,unitsSold14d',
            'segment' => 'placement',
        ],
        'V2BrandVideoCampaignPlacement' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d',
            'segment' => 'placement',
            'creativeType' => 'video'
        ],

        'V2ProductAdGroup' => [
            'deprecated' => true,
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions',
        ],
        'V2BrandAdGroup' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,dpv14d,impressions,unitsSold14d',
        ],
        'V2BrandVideoAdGroup' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d',
            'creativeType' => 'video'
        ],
        'V2DisplayAdGroup' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,bidOptimization,campaignId,campaignName,clicks,cost,currency,impressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewImpressions,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d',
            'tactic' => 'T00030',
        ],

        'V2ProductAd' => [
            'deprecated' => true,
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_PRODUCT_AD,
            'metrics' => 'adGroupId,adGroupName,asin,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions,sku',
        ],
        'V2DisplayAd' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_PRODUCT_AD,
            'metrics' => 'adGroupId,adGroupName,adId,asin,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignId,campaignName,clicks,cost,currency,impressions,sku,viewAttributedConversions14d,viewImpressions,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d',
            'tactic' => 'T00020',
        ],

        'V2ProductTarget' => [
            'deprecated' => true,
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_TARGET,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,targetId,targetingExpression,targetingText,targetingType',
        ],
        'V2BrandTarget' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_TARGET,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,dpv14d,impressions,targetId,targetingExpression,targetingText,targetingType,unitsSold14d',
        ],
        'V2BrandVideoTarget' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_TARGET,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,targetId,targetingExpression,targetingText,targetingType,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d',
            'creativeType' => 'video'
        ],
        'V2DisplayTarget' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_TARGET,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignId,campaignName,clicks,cost,currency,impressions,targetId,targetingExpression,targetingText,targetingType,viewImpressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d',
            'tactic' => 'T00020',
        ],

        'V2ProductKeyword' => [
            'deprecated' => true,
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions,keywordId,keywordStatus,keywordText,matchType',
        ],
        'V2BrandKeyword' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'metrics' => 'adGroupId,adGroupName,applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,dpv14d,impressions,keywordBid,keywordId,keywordStatus,keywordText,matchType,searchTermImpressionRank,searchTermImpressionShare,unitsSold14d',
        ],
        'V2BrandVideoKeyword' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,keywordBid,keywordId,keywordStatus,keywordText,matchType,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d',
            'creativeType' => 'video'
        ],

        'V2ProductKeywordSearchTerm' => [
            'deprecated' => true,
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions,keywordId,keywordStatus,keywordText,matchType',
            'segment' => 'query',
        ],
        'V2ProductTargetSearchTerm' => [
            'deprecated' => true,
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_TARGET,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,targetId,targetingExpression,targetingText,targetingType',
            'segment' => 'query',
        ],
        'V2BrandKeywordSearchTerm' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedSales14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,keywordBid,keywordId,keywordStatus,keywordText,matchType,searchTermImpressionRank,searchTermImpressionShare',
            'segment' => 'query',
        ],
        'V2BrandVideoKeywordSearchTerm' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedSales14d,campaignBudget,campaignBudgetType,campaignStatus,clicks,cost,impressions,keywordBid,keywordId,keywordStatus,keywordText,matchType,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr',
            'segment' => 'query',
            'creativeType' => 'video'
        ],

        'V2ProductAsin' => [
            'deprecated' => true,
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_ASIN,
            'metrics' => 'adGroupId,adGroupName,asin,attributedSales14dOtherSKU,attributedSales1dOtherSKU,attributedSales30dOtherSKU,attributedSales7dOtherSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dOtherSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dOtherSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dOtherSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dOtherSKU,campaignId,campaignName,currency,matchType,otherAsin,sku,targetingExpression,targetingText,targetingType',
            'campaignType' => 'sponsoredProducts',
        ],
        'V2DisplayAsin' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_ASIN,
            'metrics' => 'adGroupId,adGroupName,asin,attributedSales14dOtherSKU,attributedSales1dOtherSKU,attributedSales30dOtherSKU,attributedSales7dOtherSKU,attributedUnitsOrdered14dOtherSKU,attributedUnitsOrdered1dOtherSKU,attributedUnitsOrdered30dOtherSKU,attributedUnitsOrdered7dOtherSKU,campaignId,campaignName,currency,otherAsin,sku',
            'tactic' => 'T00030',
        ],

        'V2BrandVideoAd' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_AD,
            'metrics' => 'adGroupId,adGroupName,applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,dpv14d,impressions,unitsSold14d,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr',
            'creativeType' => 'all'
        ],

        'V2DisplayMatchedTargetCampaign' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignBudget,campaignId,campaignName,campaignStatus,clicks,cost,costType,currency,impressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewImpressions,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d',
            'segment' => 'matchedTarget',
            'tactic' => 'T00030',
        ],
        'V2DisplayMatchedTargetAdGroup' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,bidOptimization,campaignId,campaignName,clicks,cost,currency,impressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewImpressions,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d',
            'segment' => 'matchedTarget',
            'tactic' => 'T00030',//???
        ],
        'V2DisplayMatchedTarget' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_TARGET,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignId,campaignName,clicks,cost,currency,impressions,targetId,targetingExpression,targetingText,targetingType,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d',
            'segment' => 'matchedTarget',
            'tactic' => 'T00030',
        ],

        'V3ProductCampaign' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'reportTypeId' => "spCampaigns",
            'adProduct' => self::AD_PRODUCT_PRODUCT,
            'groupBy' => ["campaign"],
            'metrics' => self::V3REPORT_METRICS['spCampaigns']['basic']
                . ',' . self::V3REPORT_METRICS['spCampaigns']['campaign'],
        ],
        'V3ProductCampaignPlacement' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'reportTypeId' => "spCampaigns",
            'adProduct' => self::AD_PRODUCT_PRODUCT,
            'groupBy' => ["campaign", "campaignPlacement"],
            'metrics' => self::V3REPORT_METRICS['spCampaigns']['basic']
                . ',' . self::V3REPORT_METRICS['spCampaigns']['campaign']
                . ',' . self::V3REPORT_METRICS['spCampaigns']['campaignPlacement'],
        ],
        'V3ProductAdGroup' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'reportTypeId' => "spCampaigns",
            'adProduct' => self::AD_PRODUCT_PRODUCT,
            'groupBy' => ["campaign", "adGroup"],
            'metrics' => self::V3REPORT_METRICS['spCampaigns']['basic']
                . ',' . self::V3REPORT_METRICS['spCampaigns']['campaign']
                . ',' . self::V3REPORT_METRICS['spCampaigns']['adGroup'],
        ],
        'V3ProductAd' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_PRODUCT_AD,
            'reportTypeId' => "spAdvertisedProduct",
            'adProduct' => self::AD_PRODUCT_PRODUCT,
            'groupBy' => ["advertiser"],
            'metrics' => self::V3REPORT_METRICS['spAdvertisedProduct']['basic'],
        ],
        'V3ProductTarget' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_TARGET,
            'reportTypeId' => "spTargeting",
            'adProduct' => self::AD_PRODUCT_PRODUCT,
            'groupBy' => ["targeting"],
            'metrics' => self::V3REPORT_METRICS['spTargeting']['basic']
                . ',' . self::V3REPORT_METRICS['spTargeting']['targeting'],
        ],
        'V3ProductSearchTerm' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_TARGET,
            'reportTypeId' => "spSearchTerm",
            'adProduct' => self::AD_PRODUCT_PRODUCT,
            'groupBy' => ["searchTerm"],
            'metrics' => self::V3REPORT_METRICS['spSearchTerm']['basic']
                . ',' . self::V3REPORT_METRICS['spSearchTerm']['searchTerm'],
        ],
        'V3ProductAsin' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'reportTypeId' => "spPurchasedProduct",
            'adProduct' => self::AD_PRODUCT_PRODUCT,
            'groupBy' => ["asin"],
            'metrics' => self::V3REPORT_METRICS['spPurchasedProduct']['basic'],
        ],
    ];

    public const SNAPSHOT_TYPES = [
        'V2ProductCampaign' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'stateFilter' => 'enabled,paused,archived',
        ],
        'V2BrandCampaign' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'stateFilter' => 'enabled,paused,archived',
        ],
        'V3DisplayCampaign' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'stateFilter' => 'enabled,paused,archived',
        ],

        'V2ProductAdGroup' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'stateFilter' => 'enabled,paused,archived',
        ],
        'V3DisplayAdGroup' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'stateFilter' => 'enabled,paused,archived',
        ],

        'V2ProductKeyword' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'stateFilter' => 'enabled,paused,archived',
        ],
        'V2BrandKeyword' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'stateFilter' => 'enabled,paused,archived',
        ],

        'V2ProductNegativeKeyword' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_NEGATIVE_KEYWORD,
            'stateFilter' => 'enabled,paused,archived',
        ],
        'V2ProductCampaignNegativeKeyword' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_CAMPAIGN_NEGATIVE_KEYWORD,
            'stateFilter' => 'enabled,paused,archived',
        ],

        'V2ProductAd' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_PRODUCT_AD,
            'stateFilter' => 'enabled,paused,archived',
        ],
        'V3DisplayAd' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_PRODUCT_AD,
            'stateFilter' => 'enabled,paused,archived',
        ],

        'V2ProductTarget' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_TARGET,
            'stateFilter' => 'enabled,paused,archived',
        ],
        'V3DisplayTarget' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_TARGET,
            'stateFilter' => 'enabled,paused,archived',
        ],

        'V2ProductNegativeTarget' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_NEGATIVE_TARGET,
            'stateFilter' => 'enabled,paused,archived',
        ],
    ];

    public const V3REPORT_METRICS = [
        'spCampaigns' => [
            'basic' => 'impressions, clicks, cost, purchases1d, purchases7d, purchases14d, purchases30d, purchasesSameSku1d, purchasesSameSku7d, purchasesSameSku14d, purchasesSameSku30d, unitsSoldClicks1d, unitsSoldClicks7d, unitsSoldClicks14d, unitsSoldClicks30d, sales1d, sales7d, sales14d, sales30d, attributedSalesSameSku1d, attributedSalesSameSku7d, attributedSalesSameSku14d, attributedSalesSameSku30d, unitsSoldSameSku1d, unitsSoldSameSku7d, unitsSoldSameSku14d, unitsSoldSameSku30d, kindleEditionNormalizedPagesRead14d, kindleEditionNormalizedPagesRoyalties14d, date, startDate, endDate, campaignBiddingStrategy, costPerClick, clickThroughRate, spend',
            'campaign' => 'campaignName, campaignId, campaignStatus, campaignBudgetAmount, campaignBudgetType, campaignRuleBasedBudgetAmount, campaignApplicableBudgetRuleId, campaignApplicableBudgetRuleName, campaignBudgetCurrencyCode',
            'adGroup' => 'adGroupName, adGroupId, adStatus',
            'campaignPlacement' => 'placementClassification',
        ],
        'spTargeting' => [
            'basic' => 'impressions, clicks, costPerClick, clickThroughRate, cost, purchases1d, purchases7d, purchases14d, purchases30d, purchasesSameSku1d, purchasesSameSku7d, purchasesSameSku14d, purchasesSameSku30d, unitsSoldClicks1d, unitsSoldClicks7d, unitsSoldClicks14d, unitsSoldClicks30d, sales1d, sales7d, sales14d, sales30d, attributedSalesSameSku1d, attributedSalesSameSku7d, attributedSalesSameSku14d, attributedSalesSameSku30d, unitsSoldSameSku1d, unitsSoldSameSku7d, unitsSoldSameSku14d, unitsSoldSameSku30d, kindleEditionNormalizedPagesRead14d, kindleEditionNormalizedPagesRoyalties14d, salesOtherSku7d, unitsSoldOtherSku7d, acosClicks7d, acosClicks14d, roasClicks7d, roasClicks14d, keywordId, keyword, campaignBudgetCurrencyCode, date, startDate, endDate, portfolioId, campaignName, campaignId, campaignBudgetType, campaignBudgetAmount, campaignStatus, keywordBid, adGroupName, adGroupId, keywordType, matchType, targeting',
            'targeting' => 'adKeywordStatus',
        ],
        'spSearchTerm' => [
            'basic' => 'impressions, clicks, costPerClick, clickThroughRate, cost, purchases1d, purchases7d, purchases14d, purchases30d, purchasesSameSku1d, purchasesSameSku7d, purchasesSameSku14d, purchasesSameSku30d, unitsSoldClicks1d, unitsSoldClicks7d, unitsSoldClicks14d, unitsSoldClicks30d, sales1d, sales7d, sales14d, sales30d, attributedSalesSameSku1d, attributedSalesSameSku7d, attributedSalesSameSku14d, attributedSalesSameSku30d, unitsSoldSameSku1d, unitsSoldSameSku7d, unitsSoldSameSku14d, unitsSoldSameSku30d, kindleEditionNormalizedPagesRead14d, kindleEditionNormalizedPagesRoyalties14d, salesOtherSku7d, unitsSoldOtherSku7d, acosClicks7d, acosClicks14d, roasClicks7d, roasClicks14d, keywordId, keyword, campaignBudgetCurrencyCode, date, startDate, endDate, portfolioId, searchTerm, campaignName, campaignId, campaignBudgetType, campaignBudgetAmount, campaignStatus, keywordBid, adGroupName, adGroupId, keywordType, matchType, targeting',
            'searchTerm' => 'adKeywordStatus',
        ],
        'spAdvertisedProduct' => [
            'basic' => 'date, startDate, endDate, campaignName, campaignId, adGroupName, adGroupId, adId, portfolioId, impressions, clicks, costPerClick, clickThroughRate, cost, spend, campaignBudgetCurrencyCode, campaignBudgetAmount, campaignBudgetType, campaignStatus, advertisedAsin, advertisedSku, purchases1d, purchases7d, purchases14d, purchases30d, purchasesSameSku1d, purchasesSameSku7d, purchasesSameSku14d, purchasesSameSku30d, unitsSoldClicks1d, unitsSoldClicks7d, unitsSoldClicks14d, unitsSoldClicks30d, sales1d, sales7d, sales14d, sales30d, attributedSalesSameSku1d, attributedSalesSameSku7d, attributedSalesSameSku14d, attributedSalesSameSku30d, salesOtherSku7d, unitsSoldSameSku1d, unitsSoldSameSku7d, unitsSoldSameSku14d, unitsSoldSameSku30d, unitsSoldOtherSku7d, kindleEditionNormalizedPagesRead14d, kindleEditionNormalizedPagesRoyalties14d, acosClicks7d, acosClicks14d, roasClicks7d, roasClicks14d',
            'advertiser' => '',
        ],
        'spPurchasedProduct' => [
            'basic' => 'date, startDate, endDate, portfolioId, campaignName, campaignId, adGroupName, adGroupId, keywordId, keyword, keywordType, advertisedAsin, purchasedAsin, advertisedSku, campaignBudgetCurrencyCode, matchType, unitsSoldClicks1d, unitsSoldClicks7d, unitsSoldClicks14d, unitsSoldClicks30d, sales1d, sales7d, sales14d, sales30d, purchases1d, purchases7d, purchases14d, purchases30d, unitsSoldOtherSku1d, unitsSoldOtherSku7d, unitsSoldOtherSku14d, unitsSoldOtherSku30d, salesOtherSku1d, salesOtherSku7d, salesOtherSku14d, salesOtherSku30d, purchasesOtherSku1d, purchasesOtherSku7d, purchasesOtherSku14d, purchasesOtherSku30d, kindleEditionNormalizedPagesRead14d, kindleEditionNormalizedPagesRoyalties14d',
            'asin' => '',
        ],
        'sbPurchasedProduct' => [
            'basic' => 'date, startDate, endDate, campaignBudgetCurrencyCode, campaignName, adGroupName, attributionType, purchasedAsin, productName, productCategory, sales14d, orders14d, unitsSold14d, newToBrandSales14d, newToBrandPurchases14d, newToBrandUnitsSold14d, newToBrandSalesPercentage14d, newToBrandPurchasesPercentage14d, newToBrandUnitsSoldPercentage14d',
            'purchasedAsin' => '',
        ]
    ];
}
