<?php

namespace lujie\ebay\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description This API allows 3rd party developers to translate item titles.
*/
class CommerceTranslationV1Beta extends \lujie\ebay\BaseEbayRestClient
{

    public $apiBaseUrl = 'https://api.ebay.com/commerce/translation/v1_beta';

                
    /**
     * @description This method translates listing title and listing description text from one language into another. For a full list of supported language translations, see the table in the <a href="/api-docs/commerce/translation/overview.html">API Overview</a> page.
     * @tag language
     * @param array $data 
     *      - *from* - string
     *          - The language of the input text to be translated. Not all <b>LanguageEnum</b> values are supported in this field. For a full list of supported language translations, see the table in the <a href="/api-docs/commerce/translation/overview.html">API Overview</a> page. For implementation help, refer to <a href='https://developer.ebay.com/api-docs/commerce/translation/types/api:LanguageEnum'>eBay API documentation</a>
     *      - *text* - array
     *          - The input text to translate. The maximum number of characters permitted is determined by the <code>translationContext</code> value:<ul><li><code>ITEM_TITLE</code>: 1000 characters maximum</li><li><code>ITEM_DESCRIPTION</code>: 20,000 characters maximum.<br><span class="tablenote"><b>Note:</b> When translating <code>ITEM_DESCRIPTION</code> text, HTML/CSS markup and links can be included and will not count toward this 20,000 character limit.</span></li></ul><span class="tablenote"><b>Note:</b> Currently, only one input string can be translated per API call. Support for multiple continuous text strings is expected in the future.</span>
     *      - *to* - string
     *          - The target language for the translation of the input text. Not all <b>LanguageEnum</b> values are supported in this field. For a full list of supported language translations, see the table in the <a href="/api-docs/commerce/translation/overview.html">API Overview</a> page. For implementation help, refer to <a href='https://developer.ebay.com/api-docs/commerce/translation/types/api:LanguageEnum'>eBay API documentation</a>
     *      - *translationContext* - string
     *          - Input the listing entity to be translated.<br><br><b>Valid Values:</b> <code>ITEM_TITLE</code> and <code>ITEM_DESCRIPTION</code></p> For implementation help, refer to <a href='https://developer.ebay.com/api-docs/commerce/translation/types/api:TranslationContextEnum'>eBay API documentation</a>
     * @param array $headers
     *      - *Content-Type* - string - required
     *          - This header indicates the format of the request body provided by the client. It's value should be set to <b>application/json</b>. <br><br> For more information, refer to <a href="/api-docs/static/rest-request-components.html#HTTP" target="_blank ">HTTP request headers</a>.
     * @return array
     *      - *from* - string
     *          - The enumeration value indicates the language of the input text. For implementation help, refer to <a href='https://developer.ebay.com/api-docs/commerce/translation/types/api:LanguageEnum'>eBay API documentation</a>
     *      - *to* - string
     *          - The enumeration value indicates the language of the translated text. For implementation help, refer to <a href='https://developer.ebay.com/api-docs/commerce/translation/types/api:LanguageEnum'>eBay API documentation</a>
     *      - *translations* - array
     *          - An array showing the input and translated text. Only one input string can be translated at this time. Support for multiple continuous text strings is expected in the future.
     */
    public function translate(array $data, array $headers = []): array
    {
        return $this->api("/translate", 'POST', $data, $headers);
    }
    
}
