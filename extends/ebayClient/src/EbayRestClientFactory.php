<?php

namespace lujie\ebay;

use lujie\common\account\models\Account;
use lujie\common\oauth\OAuthClientFactory;
use lujie\ebay\api\BuyBrowseV1;
use lujie\ebay\api\BuyDealV1;
use lujie\ebay\api\BuyFeedV1;
use lujie\ebay\api\BuyFeedV1Beta;
use lujie\ebay\api\BuyMarketingV1Beta;
use lujie\ebay\api\BuyMarketplaceInsightsV1Beta;
use lujie\ebay\api\BuyOfferV1Beta;
use lujie\ebay\api\BuyOrderV2;
use lujie\ebay\api\CommerceCatalogV1Beta;
use lujie\ebay\api\CommerceCharityV1;
use lujie\ebay\api\CommerceIdentityV1;
use lujie\ebay\api\CommerceMediaV1Beta;
use lujie\ebay\api\CommerceNotificationV1;
use lujie\ebay\api\CommerceTaxonomyV1;
use lujie\ebay\api\CommerceTranslationV1Beta;
use lujie\ebay\api\DeveloperAnalyticsV1Beta;
use lujie\ebay\api\DeveloperClientRegistrationV1;
use lujie\ebay\api\DeveloperKeyManagementV1;
use lujie\ebay\api\SellAccountV1;
use lujie\ebay\api\SellAccountV2;
use lujie\ebay\api\SellAnalyticsV1;
use lujie\ebay\api\SellComplianceV1;
use lujie\ebay\api\SellFeedV1;
use lujie\ebay\api\SellFinancesV1;
use lujie\ebay\api\SellFulfillmentV1;
use lujie\ebay\api\SellInventoryV1;
use lujie\ebay\api\SellLogisticsV1;
use lujie\ebay\api\SellMarketingV1;
use lujie\ebay\api\SellMetadataV1;
use lujie\ebay\api\SellNegotiationV1;
use lujie\ebay\api\SellRecommendationV1;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuth2;
use yii\httpclient\Exception;

/**
 * This class is autogenerated by the OpenAPI gii generator
 */
class EbayRestClientFactory extends BaseEbayRestClientFactory
{

    /**
     * @param Account $account
     * @return BuyBrowseV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getBuyBrowseV1(Account $account): BuyBrowseV1|OAuth2|null
    {
        return $this->createClient(BuyBrowseV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return BuyDealV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getBuyDealV1(Account $account): BuyDealV1|OAuth2|null
    {
        return $this->createClient(BuyDealV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return BuyFeedV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getBuyFeedV1(Account $account): BuyFeedV1|OAuth2|null
    {
        return $this->createClient(BuyFeedV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return BuyFeedV1Beta|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getBuyFeedV1Beta(Account $account): BuyFeedV1Beta|OAuth2|null
    {
        return $this->createClient(BuyFeedV1Beta::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return BuyMarketingV1Beta|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getBuyMarketingV1Beta(Account $account): BuyMarketingV1Beta|OAuth2|null
    {
        return $this->createClient(BuyMarketingV1Beta::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return BuyMarketplaceInsightsV1Beta|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getBuyMarketplaceInsightsV1Beta(Account $account): BuyMarketplaceInsightsV1Beta|OAuth2|null
    {
        return $this->createClient(BuyMarketplaceInsightsV1Beta::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return BuyOfferV1Beta|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getBuyOfferV1Beta(Account $account): BuyOfferV1Beta|OAuth2|null
    {
        return $this->createClient(BuyOfferV1Beta::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return BuyOrderV2|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getBuyOrderV2(Account $account): BuyOrderV2|OAuth2|null
    {
        return $this->createClient(BuyOrderV2::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return CommerceCatalogV1Beta|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getCommerceCatalogV1Beta(Account $account): CommerceCatalogV1Beta|OAuth2|null
    {
        return $this->createClient(CommerceCatalogV1Beta::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return CommerceCharityV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getCommerceCharityV1(Account $account): CommerceCharityV1|OAuth2|null
    {
        return $this->createClient(CommerceCharityV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return CommerceIdentityV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getCommerceIdentityV1(Account $account): CommerceIdentityV1|OAuth2|null
    {
        return $this->createClient(CommerceIdentityV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return CommerceMediaV1Beta|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getCommerceMediaV1Beta(Account $account): CommerceMediaV1Beta|OAuth2|null
    {
        return $this->createClient(CommerceMediaV1Beta::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return CommerceNotificationV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getCommerceNotificationV1(Account $account): CommerceNotificationV1|OAuth2|null
    {
        return $this->createClient(CommerceNotificationV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return CommerceTaxonomyV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getCommerceTaxonomyV1(Account $account): CommerceTaxonomyV1|OAuth2|null
    {
        return $this->createClient(CommerceTaxonomyV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return CommerceTranslationV1Beta|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getCommerceTranslationV1Beta(Account $account): CommerceTranslationV1Beta|OAuth2|null
    {
        return $this->createClient(CommerceTranslationV1Beta::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return DeveloperAnalyticsV1Beta|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getDeveloperAnalyticsV1Beta(Account $account): DeveloperAnalyticsV1Beta|OAuth2|null
    {
        return $this->createClient(DeveloperAnalyticsV1Beta::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return DeveloperClientRegistrationV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getDeveloperClientRegistrationV1(Account $account): DeveloperClientRegistrationV1|OAuth2|null
    {
        return $this->createClient(DeveloperClientRegistrationV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return DeveloperKeyManagementV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getDeveloperKeyManagementV1(Account $account): DeveloperKeyManagementV1|OAuth2|null
    {
        return $this->createClient(DeveloperKeyManagementV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellAccountV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellAccountV1(Account $account): SellAccountV1|OAuth2|null
    {
        return $this->createClient(SellAccountV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellAccountV2|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellAccountV2(Account $account): SellAccountV2|OAuth2|null
    {
        return $this->createClient(SellAccountV2::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellAnalyticsV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellAnalyticsV1(Account $account): SellAnalyticsV1|OAuth2|null
    {
        return $this->createClient(SellAnalyticsV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellComplianceV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellComplianceV1(Account $account): SellComplianceV1|OAuth2|null
    {
        return $this->createClient(SellComplianceV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellFeedV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellFeedV1(Account $account): SellFeedV1|OAuth2|null
    {
        return $this->createClient(SellFeedV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellFinancesV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellFinancesV1(Account $account): SellFinancesV1|OAuth2|null
    {
        return $this->createClient(SellFinancesV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellFulfillmentV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellFulfillmentV1(Account $account): SellFulfillmentV1|OAuth2|null
    {
        return $this->createClient(SellFulfillmentV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellInventoryV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellInventoryV1(Account $account): SellInventoryV1|OAuth2|null
    {
        return $this->createClient(SellInventoryV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellLogisticsV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellLogisticsV1(Account $account): SellLogisticsV1|OAuth2|null
    {
        return $this->createClient(SellLogisticsV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellMarketingV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellMarketingV1(Account $account): SellMarketingV1|OAuth2|null
    {
        return $this->createClient(SellMarketingV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellMetadataV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellMetadataV1(Account $account): SellMetadataV1|OAuth2|null
    {
        return $this->createClient(SellMetadataV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellNegotiationV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellNegotiationV1(Account $account): SellNegotiationV1|OAuth2|null
    {
        return $this->createClient(SellNegotiationV1::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SellRecommendationV1|OAuth2|null
     * @throws InvalidResponseException
     * @throws Exception
     */
    public function getSellRecommendationV1(Account $account): SellRecommendationV1|OAuth2|null
    {
        return $this->createClient(SellRecommendationV1::class, $account, $this->getConfig());
    }

}
