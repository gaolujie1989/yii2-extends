<?php

namespace lujie\amazon\advertising\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description A [Manager Account](https://advertising.amazon.com/help?ref_=a20m_us_blog_whtsnewfb2020_040120#GU3YDB26FR7XT3C8) lets you manage a group of Amazon Advertising accounts.
*/
class ManagerAccountV3 extends \lujie\amazon\advertising\BaseAmazonAdvertisingClient
{

                
    /**
     * @description Unlink Amazon Advertising accounts or advertisers with a [Manager Account](https://advertising.amazon.com/help?ref_=a20m_us_blog_whtsnewfb2020_040120#GU3YDB26FR7XT3C8).     * @tag Manager Accounts
     * @param string $managerAccountId Id of the Manager Account.
     * @param array $data 
     *      - *accounts* - array
     *          - List of Advertising accounts or advertisers to link/unlink with [Manager Account](https://advertising.amazon.com/help?ref_=a20m_us_blog_whtsnewfb2020_040120#GU3YDB26FR7XT3C8). User can pass a list with a maximum of 20 accounts/advertisers using any mix of identifiers.
     * @return array
     *      - *failedAccounts* - array
     *          - List of Advertising accounts or advertisers failed to Link/Unlink with [Manager Account](https://advertising.amazon.com/help?ref_=a20m_us_blog_whtsnewfb2020_040120#GU3YDB26FR7XT3C8).
     *      - *succeedAccounts* - array
     *          - List of Advertising accounts or advertisers successfully Link/Unlink with [Manager Account](https://advertising.amazon.com/help?ref_=a20m_us_blog_whtsnewfb2020_040120#GU3YDB26FR7XT3C8).
     */
    public function unlinkAdvertisingAccountsToManagerAccountPublicAPI(string $managerAccountId, array $data, string $contentType = 'application/vnd.updateadvertisingaccountsinmanageraccountrequest.v1+json'): array
    {
        return $this->api("/managerAccounts/{$managerAccountId}/disassociate", 'POST', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Creates a new Amazon Advertising [Manager account](https://advertising.amazon.com/help?ref_=a20m_us_blog_whtsnewfb2020_040120#GU3YDB26FR7XT3C8).     * @tag Manager Accounts
     * @param array $data Request object required to create a new Manager account.
     *      - *managerAccountName* - string
     *          - Name of the Manager account.
     *      - *managerAccountType* - string
     *          - Type of the Manager account, which indicates how the Manager account will be used. Use `Advertiser` if the Manager account will be used for **your own** products and services, or `Agency` if you are managing accounts **on behalf of your clients**.
     * @return array
     *      - *managerAccountName* - string
     *          - The name given to a Manager Account.
     *      - *linkedAccounts* - array
     *      - *managerAccountId* - string
     *          - Id of the Manager Account.
     */
    public function createManagerAccount(array $data, string $contentType = 'application/vnd.createmanageraccountrequest.v1+json'): array
    {
        return $this->api("/managerAccounts", 'POST', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                
    /**
     * @description Returns all [manager accounts](https://advertising.amazon.com/help?ref_=a20m_us_blog_whtsnewfb2020_040120#GU3YDB26FR7XT3C8) that a user has access to, along with metadata for the Amazon Ads accounts that are linked to each manager account. NOTE: A maximum of 50 linked accounts are returned for each manager account.     * @tag Manager Accounts
     * @return array
     *      - *managerAccounts* - array
     *          - List of Manager Accounts that the user has access to
     */
    public function getManagerAccountsForUser(string $contentType = 'application/vnd.getmanageraccountsresponse.v1+json'): array
    {
        return $this->api("/managerAccounts", ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Link Amazon Advertising accounts or advertisers with a [Manager Account](https://advertising.amazon.com/help?ref_=a20m_us_blog_whtsnewfb2020_040120#GU3YDB26FR7XT3C8).     * @tag Manager Accounts
     * @param string $managerAccountId Id of the Manager Account.
     * @param array $data 
     *      - *accounts* - array
     *          - List of Advertising accounts or advertisers to link/unlink with [Manager Account](https://advertising.amazon.com/help?ref_=a20m_us_blog_whtsnewfb2020_040120#GU3YDB26FR7XT3C8). User can pass a list with a maximum of 20 accounts/advertisers using any mix of identifiers.
     * @return array
     *      - *failedAccounts* - array
     *          - List of Advertising accounts or advertisers failed to Link/Unlink with [Manager Account](https://advertising.amazon.com/help?ref_=a20m_us_blog_whtsnewfb2020_040120#GU3YDB26FR7XT3C8).
     *      - *succeedAccounts* - array
     *          - List of Advertising accounts or advertisers successfully Link/Unlink with [Manager Account](https://advertising.amazon.com/help?ref_=a20m_us_blog_whtsnewfb2020_040120#GU3YDB26FR7XT3C8).
     */
    public function linkAdvertisingAccountsToManagerAccountPublicAPI(string $managerAccountId, array $data, string $contentType = 'application/vnd.updateadvertisingaccountsinmanageraccountrequest.v1+json'): array
    {
        return $this->api("/managerAccounts/{$managerAccountId}/associate", 'POST', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
    
}
