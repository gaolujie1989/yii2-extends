<?php

namespace lujie\plentyMarkets\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The plentymarkets REST API expands the functionality of the plentymarkets CMS and allows access to resources, i.e. data records, via unique URI paths
*/
class LegalInformation extends \lujie\plentyMarkets\BasePlentyMarketsRestClient
{

                
    /**
     * @description Return legal texts of all types
     * @tag LegalInformation
     * @param int $plentyId 
     * @param int $lang 
     */
    public function getLegalinformationAllByPlentyIdLang(int $plentyId, int $lang)
    {
        return $this->api("/rest/legalinformation/all/{$plentyId}/{$lang}");
    }
                
    /**
     * @description if we send only plainText it will be saved in htmlText
     * @tag LegalInformation
     * @param int $plentyId 
     * @param int $lang 
     */
    public function createLegalinformationAllByPlentyIdLang(int $plentyId, int $lang)
    {
        return $this->api("/rest/legalinformation/all/{$plentyId}/{$lang}", 'POST');
    }
                    
    /**
     * @description Gets legal information of an online store. The plenty ID of the store , the language and the type of legal information must be specified. The language must be specified as ISO 639-1 code.
     * @tag LegalInformation
     * @param int $plentyId The plenty ID of the online store.
     * @param string $lang The language of the legal information text. The language must be specified as ISO 639-1 code, e.g. en for English
     * @param int $type 
     * @return array
     *      - *plentyId* - integer
     *          - The unique identifier of the plenty client
     *      - *lang* - string
     *          - The language of the legal information text
     *      - *type* - string
     *          - The type of the legal information text. The types available are:
<ul>
<li>TermsConditions</li>
<li>CancellationRights</li>
<li>PrivacyPolicy</li>
<li>LegalDisclosure</li>
<li>WithdrawalForm</li>
</ul>
     *      - *plainText* - string
     *          - The text value of the legal information text
     *      - *htmlText* - string
     *          - The html value of the legal information text
     */
    public function getLegalinformationByPlentyIdLangType(int $plentyId, string $lang, int $type): array
    {
        return $this->api("/rest/legalinformation/{$plentyId}/{$lang}/{$type}");
    }
                
    /**
     * @description Saves a legal information for an online store. The plenty ID of the online store, the language of the legal information and the type of the legal information must be specified. The language must be specified as ISO 639-1 code.
Existing legal information will be overwritten.
     * @tag LegalInformation
     * @param int $plentyId The plenty ID of the online store
     * @param string $lang The language of the legal information text. The language must be specified as ISO 639-1 code, e.g. en for English
     * @param int $type 
     * @param array $data 
     * @return array
     *      - *plentyId* - integer
     *          - The unique identifier of the plenty client
     *      - *lang* - string
     *          - The language of the legal information text
     *      - *type* - string
     *          - The type of the legal information text. The types available are:
<ul>
<li>TermsConditions</li>
<li>CancellationRights</li>
<li>PrivacyPolicy</li>
<li>LegalDisclosure</li>
<li>WithdrawalForm</li>
</ul>
     *      - *plainText* - string
     *          - The text value of the legal information text
     *      - *htmlText* - string
     *          - The html value of the legal information text
     */
    public function updateLegalinformationByPlentyIdLangType(int $plentyId, string $lang, int $type, array $data): array
    {
        return $this->api("/rest/legalinformation/{$plentyId}/{$lang}/{$type}", 'PUT', $data);
    }
    
}
