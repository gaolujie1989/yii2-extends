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

    public const REPORT_STATUS_IN_PROGRESS = 'IN_PROGRESS';
    public const REPORT_STATUS_SUCCESS = 'SUCCESS';
    public const REPORT_STATUS_FAILURE = 'FAILURE';

    public const AD_TYPE_PRODUCT = 'sp';
    public const AD_TYPE_BRAND = 'hsa';
    public const AD_TYPE_DISPLAY = 'sd';

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

    public const REPORT_TYPES = [
        'productCampaign' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,currency,impressions',
        ],
        'brandCampaign' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,dpv14d,impressions,unitsSold14d',
        ],
        'brandVideoCampaign' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,dpv14d,impressions,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d',
            'creativeType' => 'video'
        ],
        'displayCampaign' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignBudget,campaignId,campaignName,campaignStatus,clicks,cost,costType,currency,impressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewImpressions,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d',
            'tactic' => 'T00020',
        ],

        'productCampaignPlacement' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,bidPlus,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,currency,impressions',
            'segment' => 'placement',
        ],
        'brandCampaignPlacement' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,dpv14d,impressions,unitsSold14d',
            'segment' => 'placement',
        ],
        'brandVideoCampaignPlacement' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d',
            'segment' => 'placement',
            'creativeType' => 'video'
        ],

        'productAdGroup' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions',
        ],
        'brandAdGroup' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,dpv14d,impressions,unitsSold14d',
        ],
        'brandVideoAdGroup' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d',
            'creativeType' => 'video'
        ],
        'displayAdGroup' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,bidOptimization,campaignId,campaignName,clicks,cost,currency,impressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewImpressions,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d',
            'tactic' => 'T00030',
        ],

        'productAd' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_PRODUCT_AD,
            'metrics' => 'adGroupId,adGroupName,asin,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions,sku',
        ],
        'displayAd' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_PRODUCT_AD,
            'metrics' => 'adGroupId,adGroupName,adId,asin,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignId,campaignName,clicks,cost,currency,impressions,sku,viewAttributedConversions14d,viewImpressions,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d',
            'tactic' => 'T00020',
        ],

        'productTarget' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_TARGET,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,targetId,targetingExpression,targetingText,targetingType',
        ],
        'brandTarget' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_TARGET,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,dpv14d,impressions,targetId,targetingExpression,targetingText,targetingType,unitsSold14d',
        ],
        'brandVideoTarget' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_TARGET,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,targetId,targetingExpression,targetingText,targetingType,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d',
            'creativeType' => 'video'
        ],
        'displayTarget' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_TARGET,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignId,campaignName,clicks,cost,currency,impressions,targetId,targetingExpression,targetingText,targetingType,viewImpressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d',
            'tactic' => 'T00020',
        ],

        'productKeyword' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions,keywordId,keywordStatus,keywordText,matchType',
        ],
        'brandKeyword' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'metrics' => 'adGroupId,adGroupName,applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,dpv14d,impressions,keywordBid,keywordId,keywordStatus,keywordText,matchType,searchTermImpressionRank,searchTermImpressionShare,unitsSold14d',
        ],
        'brandVideoKeyword' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,keywordBid,keywordId,keywordStatus,keywordText,matchType,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d',
            'creativeType' => 'video'
        ],

        'productKeywordSearchTerm' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions,keywordId,keywordStatus,keywordText,matchType',
            'segment' => 'query',
        ],
        'productTargetSearchTerm' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_TARGET,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,targetId,targetingExpression,targetingText,targetingType',
            'segment' => 'query',
        ],
        'brandKeywordSearchTerm' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedSales14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,keywordBid,keywordId,keywordStatus,keywordText,matchType,searchTermImpressionRank,searchTermImpressionShare',
            'segment' => 'query',
        ],
        'brandVideoKeywordSearchTerm' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_KEYWORD,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedSales14d,campaignBudget,campaignBudgetType,campaignStatus,clicks,cost,impressions,keywordBid,keywordId,keywordStatus,keywordText,matchType,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr',
            'segment' => 'query',
            'creativeType' => 'video'
        ],

        'productAsin' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_ASIN,
            'metrics' => 'adGroupId,adGroupName,asin,attributedSales14dOtherSKU,attributedSales1dOtherSKU,attributedSales30dOtherSKU,attributedSales7dOtherSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dOtherSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dOtherSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dOtherSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dOtherSKU,campaignId,campaignName,currency,matchType,otherAsin,sku,targetingExpression,targetingText,targetingType',
            'campaignType' => 'sponsoredProducts',
        ],
        'displayAsin' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_ASIN,
            'metrics' => 'adGroupId,adGroupName,asin,attributedSales14dOtherSKU,attributedSales1dOtherSKU,attributedSales30dOtherSKU,attributedSales7dOtherSKU,attributedUnitsOrdered14dOtherSKU,attributedUnitsOrdered1dOtherSKU,attributedUnitsOrdered30dOtherSKU,attributedUnitsOrdered7dOtherSKU,campaignId,campaignName,currency,otherAsin,sku',
            'tactic' => 'T00030',
        ],

        'brandVideoAd' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_AD,
            'metrics' => 'adGroupId,adGroupName,applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,dpv14d,impressions,unitsSold14d,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr',
            'creativeType' => 'all'
        ],

        'displayMatchedTargetCampaign' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
            'metrics' => 'attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignBudget,campaignId,campaignName,campaignStatus,clicks,cost,costType,currency,impressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewImpressions,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d',
            'segment' => 'matchedTarget',
            'tactic' => 'T00030',
        ],
        'displayMatchedTargetAdGroup' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,bidOptimization,campaignId,campaignName,clicks,cost,currency,impressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewImpressions,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d',
            'segment' => 'matchedTarget',
            'tactic' => 'T00030',//???
        ],
        'displayMatchedTarget' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_TARGET,
            'metrics' => 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignId,campaignName,clicks,cost,currency,impressions,targetId,targetingExpression,targetingText,targetingType,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d',
            'segment' => 'matchedTarget',
            'tactic' => 'T00030',
        ],
    ];

    public const SNAPSHOT_TYPES = [
        'productCampaign' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
        ],
        'brandCampaign' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
        ],
        'displayCampaign' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_CAMPAIGN,
        ],

        'productAdGroup' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
        ],
        'displayAdGroup' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_AD_GROUP,
        ],

        'productKeyword' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_KEYWORD,
        ],
        'brandKeyword' => [
            'adType' => self::AD_TYPE_BRAND,
            'recordType' => self::RECORD_TYPE_KEYWORD,
        ],

        'productNegativeKeyword' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_NEGATIVE_KEYWORD,
        ],
        'productCampaignNegativeKeyword' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_CAMPAIGN_NEGATIVE_KEYWORD,
        ],

        'productAd' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_PRODUCT_AD,
        ],
        'displayAd' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_PRODUCT_AD,
        ],

        'productTarget' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_TARGET,
        ],
        'displayTarget' => [
            'adType' => self::AD_TYPE_DISPLAY,
            'recordType' => self::RECORD_TYPE_TARGET,
        ],

        'productNegativeTarget' => [
            'adType' => self::AD_TYPE_PRODUCT,
            'recordType' => self::RECORD_TYPE_NEGATIVE_TARGET,
        ],
    ];
}
