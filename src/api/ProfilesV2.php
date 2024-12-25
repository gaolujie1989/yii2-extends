<?php

namespace lujie\amazon\advertising\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description Profiles represent an advertiser and their account's marketplace, and are used in all subsequent API calls via a management scope, `Amazon-Advertising-API-Scope`. Reports and all entity management operations are associated with a single profile. Advertisers cannot have more than one profile for each marketplace.

Advertisers who operate in more than one marketplace (for example, Amazon.com, Amazon.co.uk, Amazon.co.jp) will have only one profile associated with each marketplace. [**See this link**](https://advertising.amazon.com/API/docs/en-us/info/api-overview#api-endpoints) for a list of marketplaces associated with each endpoint.

To retrieve your profile IDs, call the listProfiles operation, and include a valid authorization access token in the header. Use a `profileId` from the returned list as the value for the management scope (`Amazon-Advertising-API-Scope`) in the headers for subsequent API calls.
*/
class ProfilesV2 extends \lujie\amazon\advertising\BaseAmazonAdvertisingClient
{

                
    /**
     * @description Note that this operation does not return a response unless the current account has created at least one campaign using the advertising console.     * @tag Profiles
     * @param array $query
     *      - *apiProgram* - string - optional
     *          - Filters response to include profiles that have permissions for the specified Advertising API program only. Setting `apiProgram=billing` filters the response to include only profiles to which the user and application associated with the access token have permission to view or edit billing information.
     *      - *accessLevel* - string - optional
     *          - Filters response to include profiles that have specified permissions for the specified Advertising API program only. Currently, the only supported access level is `view` and `edit`. Setting `accessLevel=view` filters the response to include only profiles to which the user and application associated with the access token have view permission to the provided api program.
     *      - *profileTypeFilter* - string - optional
     *          - Filters response to include profiles that are of the specified types in the comma-delimited list. Default is all types. Note that this filter performs an inclusive AND operation on the types.
     *      - *validPaymentMethodFilter* - string - optional
     *          - Filter response to include profiles that have valid payment methods. Default is to include all profiles. Setting this filter to `true` returns only profiles with either no `validPaymentMethod` field, or the `validPaymentMethod` field set to `true`.  Setting this to `false` returns profiles with the `validPaymentMethod` field set to `false` only.
     */
    public function listProfiles(array $query = []): void
    {
        $this->api(array_merge(["/v2/profiles"], $query));
    }
                
    /**
     * @description Note that this operation is only used for Sellers using Sponsored Products. This operation is not enabled for vendor type accounts.     * @tag Profiles
     * @param array $data 
     */
    public function updateProfiles(array $data, string $contentType = 'application/json'): void
    {
        $this->api("/v2/profiles", 'PUT', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description This operation does not return a response unless the current account has created at least one campaign using the advertising console.     * @tag Profiles
     * @param int $profileId 
     * @return array
     *      - *profileId* - integer
     *      - *countryCode* - 
     *      - *currencyCode* - string
     *          - The currency used for all monetary values for entities under this profile.
|Region|`countryCode`|Country Name|`currencyCode`|
|-----|------|------|------|
|NA|BR|Brazil|BRL|
|NA|CA|Canada|CAD|
|NA|MX|Mexico|MXN|
|NA|US|United States|USD|
|EU|AE|United Arab Emirates|AED|
|EU|BE|Belgium|EUR|
|EU|DE|Germany|EUR|
|EU|EG|Egypt|EGP|
|EU|ES|Spain|EUR|
|EU|FR|France|EUR|
|EU|IN|India|INR|
|EU|IT|Italy|EUR|
|EU|NL|The Netherlands|EUR|
|EU|PL|Poland|PLN|
|EU|SA|Saudi Arabia|SAR|
|EU|SE|Sweden|SEK|
|EU|TR|Turkey|TRY|
|EU|UK|United Kingdom|GBP|
|EU|ZA| South Africa | ZAR|
|FE|AU|Australia|AUD|
|FE|JP|Japan|JPY|
|FE|SG|Singapore|SGD|
     *      - *dailyBudget* - number
     *          - Note that this field applies to Sponsored Product campaigns for seller type accounts only. Not supported for vendor type accounts.
     *      - *timezone* - string
     *          - The time zone used for all date-based campaign management and reporting.
|Region|`countryCode`|Country Name|`timezone`|
|------|-----|-----|------|
|NA|BR|Brazil|America/Sao_Paulo|
|NA|CA|Canada|America/Los_Angeles|
|NA|MX|Mexico|America/Los_Angeles|
|NA|US|United States|America/Los_Angeles|
|EU|AE|United Arab Emirates|Asia/Dubai|
|EU|BE|Belgium|Europe/Brussels|
|EU|DE|Germany|Europe/Paris|
|EU|EG|Egypt|Africa/Cairo|
|EU|ES|Spain|Europe/Paris|
|EU|FR|France|Europe/Paris|
|EU|IN|India|Asia/Kolkata|
|EU|IT|Italy|Europe/Paris|
|EU|NL|The Netherlands|Europe/Amsterdam|
|EU|PL|Poland|Europe/Warsaw|
|EU|SA|Saudi Arabia|Asia/Riyadh|
|EU|SE|Sweden|Europe/Stockholm|
|EU|TR|Turkey|Europe/Istanbul|
|EU|UK|United Kingdom|Europe/London|
|EU|ZA| South Africa | Africa/Johannesburg |
|FE|AU|Australia|Australia/Sydney|
|FE|JP|Japan|Asia/Tokyo|
|FE|SG|Singapore|Asia/Singapore|
     *      - *accountInfo* - 
     */
    public function getProfileById(int $profileId): array
    {
        return $this->api("/v2/profiles/{$profileId}");
    }
    
}
