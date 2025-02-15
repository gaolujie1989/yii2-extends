<?php

namespace lujie\amazon\advertising\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description Public API for managing advertising accounts.
*/
class AdvertisingAccountsV3 extends \lujie\amazon\advertising\BaseAmazonAdvertisingClient
{

                
    /**
     * @description Request attributes of a given advertising account.

**Requires one of these permissions**:
[]     * @tag Account
     * @param string $advertisingAccountId This is the global advertising account Id from the client.
     * @return array
     *      - *adsAccount* - 
     */
    public function getAccount(string $advertisingAccountId, string $contentType = 'application/vnd.accountresource.v1+json'): array
    {
        return $this->api("/adsAccounts/{$advertisingAccountId}", 'GET', [], ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Create a new UUID terms token for the customer to accept advertising terms

**Requires one of these permissions**:
[]     * @tag Terms Token
     * @param array $data 
     *      - *termsType* - 
     * @return array
     *      - *termsUrl* - string
     *          - The link to advertising terms page where the advertiser can view and accept.
     *      - *termsToken* - string
     *          - A Terms Token refers to an UUID token used for terms and conditions acceptance
     */
    public function createTermsToken(array $data, string $contentType = 'application/vnd.GlobalRegistrationService.TermsTokenResource.v1.0+json'): array
    {
        return $this->api("/termsTokens", 'POST', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description List all advertising accounts for the user associated with the access token.

**Requires one of these permissions**:
[]     * @tag Account
     * @param array $data 
     *      - *nextToken* - string
     *          - The token is used to fetch the next page of results if they exist.
     *      - *maxResults* - number
     * @return array
     *      - *nextToken* - string
     *      - *adsAccounts* - array
     */
    public function listAdsAccounts(array $data, string $contentType = 'application/vnd.listaccountsresource.v1+json'): array
    {
        return $this->api("/adsAccounts/list", 'POST', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Create a new advertising account tied to a specific Amazon vendor, seller or author, or to a business who does not sell on Amazon.

**Requires one of these permissions**:
[]     * @tag Account
     * @param array $data 
     *      - *associations* - array
     *          - Associations you would like to link to this advertising account, could be Amazon Vendor, Seller, or just a regular business
     *      - *countryCodes* - array
     *          - The countries that you want this account to operate in.
     *      - *accountName* - string
     *          - Account names are typically the name of the company or brand being advertised. We recommend that you avoid using personal details such as first name, last name, phone number, social security number, credit card or other personally identifiable information.
     *      - *termsToken* - string
     *          - We recommend you do not provide this field since we can determine if the customer has accepted the terms for you. An obfuscated identifier of the termsToken, which is activated when an advertisers accepts the Amazon Ads Agreement in relation to the ads account being register.
     * @return array
     *      - *adsAccount* - 
     */
    public function registerAdsAccount(array $data, string $contentType = 'application/vnd.registeradsaccountresource.v1+json'): array
    {
        return $this->api("/adsAccounts", 'POST', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Get the terms token status for the customer

**Requires one of these permissions**:
[]     * @tag Terms Token
     * @param string $termsToken A Terms Token refers to an UUID token used for terms and conditions acceptance

     * @return array
     *      - *termsTokenStatus* - 
     *      - *termsType* - 
     */
    public function getTermsToken(string $termsToken, string $contentType = 'application/vnd.termstokenresource.v1+json'): array
    {
        return $this->api("/termsTokens/{$termsToken}", 'GET', [], ['content-type' => $contentType, 'accept' => $contentType]);
    }
    
}
