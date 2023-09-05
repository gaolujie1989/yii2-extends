<?php

namespace lujie\plentyMarkets\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The plentymarkets REST API expands the functionality of the plentymarkets CMS and allows access to resources, i.e. data records, via unique URI paths
*/
class CustomerContract extends \lujie\plentyMarkets\BasePlentyMarketsRestClient
{

                
    /**
     * @description Gets a list of all contracts
     * @tag CustomerContract
     * @return array
     */
    public function getCustomerContracts(): array
    {
        return $this->api("/rest/customer_contracts");
    }
                
    /**
     * @description Creates a new contract
     * @tag CustomerContract
     * @return array
     *      - *id* - string
     *      - *contractLang* - string
     *      - *contractName* - string
     *      - *contractVisible* - boolean
     */
    public function createCustomerContract(): array
    {
        return $this->api("/rest/customer_contracts", 'POST');
    }
                    
    /**
     * @description Gets a single contract based on its ID
     * @tag CustomerContract
     * @param string $contractId The ID of the contract
     * @return array
     *      - *id* - string
     *      - *contractLang* - string
     *      - *contractName* - string
     *      - *contractVisible* - boolean
     */
    public function getCustomerContractByContractId(string $contractId): array
    {
        return $this->api("/rest/customer_contracts/{$contractId}");
    }
                    
    /**
     * @description Starts the download of contract document based on the contract's ID
     * @tag CustomerContract
     * @param int $contractId 
     */
    public function getCustomerContractsDocumentByContractId(int $contractId): void
    {
        $this->api("/rest/customer_contracts/{$contractId}/document");
    }
                    
    /**
     * @description Returns the signing of a contract based on the contract's ID
     * @tag CustomerContract
     * @param string $contractId The ID of the contract
     * @return array
     *      - *contractId* - string
     *      - *contactId* - string
     *      - *signerName* - string
     *      - *createdAt* - string
     */
    public function getCustomerContractsSignByContractId(string $contractId): array
    {
        return $this->api("/rest/customer_contracts/{$contractId}/sign");
    }
                
    /**
     * @description Signs a contract based on the contract's ID
     * @tag CustomerContract
     * @param string $contractId The ID of the contract
     * @return array
     *      - *contractId* - string
     *      - *contactId* - string
     *      - *signerName* - string
     *      - *createdAt* - string
     */
    public function createCustomerContractsSignByContractId(string $contractId): array
    {
        return $this->api("/rest/customer_contracts/{$contractId}/sign", 'POST');
    }
                    
    /**
     * @description Starts the download of signed contract document based on the contract's ID
     * @tag CustomerContract
     * @param int $contractId 
     */
    public function getCustomerContractsSignDocumentByContractId(int $contractId): void
    {
        $this->api("/rest/customer_contracts/{$contractId}/sign/document");
    }
    
}
