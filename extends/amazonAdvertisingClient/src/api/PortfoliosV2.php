<?php

namespace lujie\amazon\advertising\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description Portfolios consist of campaigns that are grouped together and linked to a distinct Advertiser Account. The term 'advertiser' refers to a brand, entity, account identifier, or claim identifier. Multiple portfolios are supported within an Advertiser Account.
*/
class PortfoliosV2 extends \lujie\amazon\advertising\BaseAmazonAdvertisingClient
{

                
    /**
     * @description Retrieves a list of portfolios, optionally filtered by identifier, name, or state. Note that this operation returns a maximum of 100 portfolios.     * @tag Portfolios

     */
    public function listPortfolios(string $contentType = 'application/json'): void
    {
        $this->api("/v2/portfolios", 'GET', [], ['content-type' => $contentType, 'accept' => $contentType]);
    }
                
    /**
     * @description The request body is a list of portfolio resources with updated values. Note that the only valid `state` for Portfolio creation is `enabled`. Portfolios can't be updated with `state` set to `paused`, this will result in an `INVALID_ARGUMENT` error.     * @tag Portfolios
     * @param array $data A list of portfolio resources with updated values.
     */
    public function updatePortfolios(array $data, string $contentType = 'application/json'): void
    {
        $this->api("/v2/portfolios", 'PUT', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                
    /**
     * @description The request body is a list of portfolio resources to be created. Note that this operation is limited to the creation of 100 portfolios. Also note that the only valid `state` for Portfolio creation is `enabled`. Portfolios can't be created with `state` set to `paused`, this will result in an `INVALID_ARGUMENT` error.     * @tag Portfolios
     * @param array $data A list of portfolio resources with updated values.
     */
    public function createPortfolios(array $data, string $contentType = 'application/json'): void
    {
        $this->api("/v2/portfolios", 'POST', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Returns a Portfolio object for a requested portfolio.     * @tag Portfolios
     * @param int $portfolioId The identifier of an existing portfolio.
     * @return array
     *      - *portfolioId* - number
     *          - The portfolio identifier.
     *      - *name* - string
     *          - The portfolio name.
     *      - *budget* - object
     *      - *inBudget* - boolean
     *          - Indicates the current budget status of the portfolio. Set to `true` if the portfolio is in budget, set to `false` if the portfolio is out of budget.
     *      - *state* - string
     *          - The current state of the portfolio.
     */
    public function getPortfolio(int $portfolioId, string $contentType = 'application/json'): array
    {
        return $this->api("/v2/portfolios/{$portfolioId}", 'GET', [], ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Retrieves a list of portfolios with an extended set of properties, optionally filtered by identifier, name, or state. Note that this operation returns a maximum of 100 portfolios.     * @tag Portfolios extended

     */
    public function listPortfoliosEx(string $contentType = 'application/json'): void
    {
        $this->api("/v2/portfolios/extended", 'GET', [], ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Gets an extended set of properties for a portfolio specified by identifier.     * @tag Portfolios extended
     * @param int $portfolioId The identifier of an existing portfolio.
     * @return array
     */
    public function listPortfolioEx(int $portfolioId, string $contentType = 'application/json'): array
    {
        return $this->api("/v2/portfolios/extended/{$portfolioId}", 'GET', [], ['content-type' => $contentType, 'accept' => $contentType]);
    }
    
}
