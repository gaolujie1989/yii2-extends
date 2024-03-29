<?php

namespace lujie\plentyMarkets\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The plentymarkets REST API expands the functionality of the plentymarkets CMS and allows access to resources, i.e. data records, via unique URI paths
*/
class Authentication extends \lujie\plentyMarkets\BasePlentyMarketsRestClient
{

                
    /**
     * @description Checks the credentials.
     * @tag Authentication

     */
    public function createCheckPassword()
    {
        return $this->api("/rest/check_password", 'POST');
    }
                    
    /**
     * @description Checks the credentials.
     * @tag Authentication

     */
    public function createCheckPin()
    {
        return $this->api("/rest/check_pin", 'POST');
    }
                    
    /**
     * @description Logs in to plentymarkets with your back end user credentials. The login call returns a JSON object that contains information, such as the access token and the refresh token.
     * @tag Authentication
     * @param array $data 
     */
    public function createLogin(array $data)
    {
        return $this->api("/rest/login", 'POST', $data);
    }
                    
    /**
     * @description Refreshes the access token using the refresh token. The refresh token is part of the login call response.
     * @tag Authentication
     * @return array
     *      - *accessToken* - string
     *          - The access token. Required for REST call authentication.
     *      - *tokenType* - string
     *          - The token type. The token type is Bearer.
     *      - *expiresIn* - integer
     *          - The expiration time in seconds that the access token is valid for
     *      - *refreshToken* - string
     *          - The refresh token. Required for refreshing the access token.
     */
    public function createLoginRefresh(): array
    {
        return $this->api("/rest/login/refresh", 'POST');
    }
                    
    /**
     * @description Logs out the back end user from plentymarkets. The access token expires.
     * @tag Authentication

     */
    public function createLogout()
    {
        return $this->api("/rest/logout", 'POST');
    }
                    
    /**
     * @description Quick login user by client name and client secret
     * @tag Authentication

     */
    public function createQuickLogin()
    {
        return $this->api("/rest/quick_login", 'POST');
    }
                    
    /**
     * @description Get the limit of of concurrent sessions.
     * @tag Authentication

     */
    public function getSessionLimits()
    {
        return $this->api("/rest/session_limits");
    }
                    
    /**
     * @description The user call returns a JSON object that contains information about the currently logged in API-User.
     * @tag Authentication
     * @return array
     *      - *id* - integer
     *      - *user* - string
     *      - *realName* - string
     *      - *lang* - string
     *      - *ipLimit* - string
     *      - *ustatus* - boolean
     *      - *memberId* - integer
     *      - *timestamp* - string
     *      - *email* - string
     *      - *timezone* - string
     *      - *googleEmail* - string
     *      - *skype* - string
     *      - *ical* - string
     *      - *psConfig* - boolean
     *      - *psItem* - boolean
     *      - *psEbay* - boolean
     *      - *psStock* - boolean
     *      - *psCustomer* - boolean
     *      - *psOrder* - boolean
     *      - *psStats* - boolean
     *      - *psData* - boolean
     *      - *pcConfig* - boolean
     *      - *pcContent* - boolean
     *      - *pcNewsletter* - boolean
     *      - *pcLayout* - boolean
     *      - *pcDialog* - boolean
     *      - *pcStats* - boolean
     *      - *pcData* - boolean
     *      - *pcBlog* - boolean
     *      - *signature* - string
     *      - *color* - string
     *      - *eks* - boolean
     *      - *payments* - boolean
     *      - *acceptAgb* - integer
     *      - *api* - boolean
     *      - *image* - string
     *      - *delOrder* - boolean
     *      - *delArticle* - boolean
     *      - *delRecord* - boolean
     *      - *plentystat* - boolean
     *      - *plentyconnect* - boolean
     *      - *webspaceAccess* - boolean
     *      - *accessControlList* - string
     *      - *plentymarketsShippingOrderId* - boolean
     *      - *plentymarketsShippingItem* - boolean
     *      - *plentymarketsShippingAuto* - boolean
     *      - *plentymarketsShippingLabel* - boolean
     *      - *plentymarketsShippingConfig* - boolean
     *      - *warehouseId* - integer
     *      - *calendar* - boolean
     *      - *orderStatus* - string
     *      - *warehouseRepairId* - integer
     *      - *project* - boolean
     *      - *ticket* - boolean
     *      - *order* - boolean
     *      - *blog* - boolean
     *      - *lead* - boolean
     *      - *customer* - boolean
     *      - *totalVacationDays* - number
     *      - *roleId* - integer
     *      - *salutation* - string
     *      - *dataLang* - string
     *      - *disabled* - boolean
     *      - *scheduler* - boolean
     *      - *item* - boolean
     *      - *incomingItems* - boolean
     *      - *backendPluginSetId* - integer
     *          - The ID of the user's selected backend set
     */
    public function getUser(): array
    {
        return $this->api("/rest/user");
    }
                    
    /**
     * @description Reset the failed authentication attempts of a user using the UserID
     * @tag Authentication
     * @param int $id 
     */
    public function createUsersResetFailedAttemptById(int $id)
    {
        return $this->api("/rest/users/{$id}/reset_failed_attempts", 'POST');
    }
    
}
