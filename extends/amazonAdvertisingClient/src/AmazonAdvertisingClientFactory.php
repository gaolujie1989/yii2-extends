<?php

namespace lujie\amazon\advertising;

use lujie\amazon\advertising\api\AdvertisersV3;
use lujie\amazon\advertising\api\AdvertisingAccountsV3;
use lujie\amazon\advertising\api\AdvertisingBillingV3;
use lujie\amazon\advertising\api\AdvertisingTestAccountV3;
use lujie\amazon\advertising\api\AmazonAdsAPIExportsV3;
use lujie\amazon\advertising\api\AmazonAttributionV3;
use lujie\amazon\advertising\api\AmazonMarketingStreamV3;
use lujie\amazon\advertising\api\AudiencesV3;
use lujie\amazon\advertising\api\BrandMetricsV3;
use lujie\amazon\advertising\api\ChangeHistoryV3;
use lujie\amazon\advertising\api\ConversionsAPIV3;
use lujie\amazon\advertising\api\CreativeAssetsV3;
use lujie\amazon\advertising\api\DSPAdvertiserV3;
use lujie\amazon\advertising\api\DSPAudiencesV3;
use lujie\amazon\advertising\api\DSPReportsV3;
use lujie\amazon\advertising\api\DSPV31;
use lujie\amazon\advertising\api\DataProviderV3;
use lujie\amazon\advertising\api\EligibilityV3;
use lujie\amazon\advertising\api\GoalSeekingBidderTargetKPIRecommendationV3;
use lujie\amazon\advertising\api\HashedRecordsV3;
use lujie\amazon\advertising\api\InsightsV3;
use lujie\amazon\advertising\api\LocationsV3;
use lujie\amazon\advertising\api\ManagerAccountV3;
use lujie\amazon\advertising\api\MeasurementV3;
use lujie\amazon\advertising\api\ModerationV3;
use lujie\amazon\advertising\api\OfflineReportV3;
use lujie\amazon\advertising\api\PartnerOpportunitiesV3;
use lujie\amazon\advertising\api\PersonaBuilderAPIV3;
use lujie\amazon\advertising\api\PortfoliosV2;
use lujie\amazon\advertising\api\PreModerationV3;
use lujie\amazon\advertising\api\ProductSelectorV3;
use lujie\amazon\advertising\api\ProfilesV2;
use lujie\amazon\advertising\api\RecommendationsV3;
use lujie\amazon\advertising\api\SponsoredBrandsV4;
use lujie\amazon\advertising\api\SponsoredDisplayV3;
use lujie\amazon\advertising\api\SponsoredProductsV3;
use lujie\amazon\advertising\api\StoresV3;
use lujie\common\account\models\Account;
use yii\authclient\OAuth2;
use yii\base\InvalidConfigException;

/**
 * This class is autogenerated by the OpenAPI gii generator
 */
class AmazonAdvertisingClientFactory extends BaseAmazonAdvertisingClientFactory
{

    /**
     * @param Account $account
     * @return AdvertisersV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getAdvertisersV3(Account $account): AdvertisersV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(AdvertisersV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return AdvertisingAccountsV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getAdvertisingAccountsV3(Account $account): AdvertisingAccountsV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(AdvertisingAccountsV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return AdvertisingBillingV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getAdvertisingBillingV3(Account $account): AdvertisingBillingV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(AdvertisingBillingV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return AdvertisingTestAccountV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getAdvertisingTestAccountV3(Account $account): AdvertisingTestAccountV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(AdvertisingTestAccountV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return AmazonAdsAPIExportsV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getAmazonAdsAPIExportsV3(Account $account): AmazonAdsAPIExportsV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(AmazonAdsAPIExportsV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return AmazonAttributionV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getAmazonAttributionV3(Account $account): AmazonAttributionV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(AmazonAttributionV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return AmazonMarketingStreamV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getAmazonMarketingStreamV3(Account $account): AmazonMarketingStreamV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(AmazonMarketingStreamV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return AudiencesV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getAudiencesV3(Account $account): AudiencesV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(AudiencesV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return BrandMetricsV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getBrandMetricsV3(Account $account): BrandMetricsV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(BrandMetricsV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return ChangeHistoryV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getChangeHistoryV3(Account $account): ChangeHistoryV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(ChangeHistoryV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return ConversionsAPIV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getConversionsAPIV3(Account $account): ConversionsAPIV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(ConversionsAPIV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return CreativeAssetsV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getCreativeAssetsV3(Account $account): CreativeAssetsV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(CreativeAssetsV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return DSPAdvertiserV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getDSPAdvertiserV3(Account $account): DSPAdvertiserV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(DSPAdvertiserV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return DSPAudiencesV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getDSPAudiencesV3(Account $account): DSPAudiencesV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(DSPAudiencesV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return DSPReportsV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getDSPReportsV3(Account $account): DSPReportsV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(DSPReportsV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return DSPV31|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getDSPV31(Account $account): DSPV31|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(DSPV31::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return DataProviderV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getDataProviderV3(Account $account): DataProviderV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(DataProviderV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return EligibilityV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getEligibilityV3(Account $account): EligibilityV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(EligibilityV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return GoalSeekingBidderTargetKPIRecommendationV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getGoalSeekingBidderTargetKPIRecommendationV3(Account $account): GoalSeekingBidderTargetKPIRecommendationV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(GoalSeekingBidderTargetKPIRecommendationV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return HashedRecordsV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getHashedRecordsV3(Account $account): HashedRecordsV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(HashedRecordsV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return InsightsV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getInsightsV3(Account $account): InsightsV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(InsightsV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return LocationsV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getLocationsV3(Account $account): LocationsV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(LocationsV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return ManagerAccountV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getManagerAccountV3(Account $account): ManagerAccountV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(ManagerAccountV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return MeasurementV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getMeasurementV3(Account $account): MeasurementV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(MeasurementV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return ModerationV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getModerationV3(Account $account): ModerationV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(ModerationV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return OfflineReportV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getOfflineReportV3(Account $account): OfflineReportV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(OfflineReportV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return PartnerOpportunitiesV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getPartnerOpportunitiesV3(Account $account): PartnerOpportunitiesV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(PartnerOpportunitiesV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return PersonaBuilderAPIV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getPersonaBuilderAPIV3(Account $account): PersonaBuilderAPIV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(PersonaBuilderAPIV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return PortfoliosV2|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getPortfoliosV2(Account $account): PortfoliosV2|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(PortfoliosV2::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return PreModerationV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getPreModerationV3(Account $account): PreModerationV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(PreModerationV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return ProductSelectorV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getProductSelectorV3(Account $account): ProductSelectorV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(ProductSelectorV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return ProfilesV2|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getProfilesV2(Account $account): ProfilesV2|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(ProfilesV2::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return RecommendationsV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getRecommendationsV3(Account $account): RecommendationsV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(RecommendationsV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SponsoredBrandsV4|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getSponsoredBrandsV4(Account $account): SponsoredBrandsV4|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(SponsoredBrandsV4::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SponsoredDisplayV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getSponsoredDisplayV3(Account $account): SponsoredDisplayV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(SponsoredDisplayV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return SponsoredProductsV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getSponsoredProductsV3(Account $account): SponsoredProductsV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(SponsoredProductsV3::class, $account, $this->getConfig());
    }

    /**
     * @param Account $account
     * @return StoresV3|BaseAmazonAdvertisingClient|OAuth2|null
     * @throws InvalidConfigException
     */
    public function getStoresV3(Account $account): StoresV3|BaseAmazonAdvertisingClient|OAuth2|null
    {
        return $this->createClient(StoresV3::class, $account, $this->getConfig());
    }

}
