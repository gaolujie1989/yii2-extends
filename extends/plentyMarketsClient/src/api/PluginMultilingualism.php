<?php

namespace lujie\plentyMarkets\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The plentymarkets REST API expands the functionality of the plentymarkets CMS and allows access to resources, i.e. data records, via unique URI paths
*/
class PluginMultilingualism extends \lujie\plentyMarkets\BasePlentyMarketsRestClient
{

                
    /**
     * @description Creates a new translation.
     * @tag PluginMultilingualism
     * @param array $query
     *      - *$pluginSetId* - int - required
     *          - The ID of the plugin set
     *      - *$pluginName* - string - required
     *          - The name of the plugin
     *      - *$languageCode* - string - required
     *          - The language code for the translation
     *      - *$key* - string - required
     *          - The translation key
     *      - *$value* - string - required
     *          - The value of the translation
     *      - *$fileName* - string - required
     *          - The of the file
     * @return array
     *      - *id* - integer
     *          - The ID of the translation
     *      - *pluginSetId* - integer
     *          - The ID of the plugin set
     *      - *pluginName* - string
     *          - The name of the plugin
     *      - *languageCode* - string
     *          - The code of the language
     *      - *fileName* - string
     *          - The file of the key
     *      - *key* - string
     *          - The translation key
     *      - *value* - string
     *          - The translation value
     */
    public function createLanguagesTranslation(array $query): array
    {
        return $this->api(array_merge(["/rest/languages/translations"], $query), 'POST');
    }
                    
    /**
     * @description Deletes a translation. The ID of the translation must be specified.
     * @tag PluginMultilingualism
     * @param int $translationId 
     * @param array $query
     *      - *$translationId* - int - required
     *          - The ID of the translation
     */
    public function deleteLanguagesTranslationByTranslationId(int $translationId, array $query)
    {
        return $this->api(array_merge(["/rest/languages/translations/{$translationId}"], $query), 'DELETE');
    }
                
    /**
     * @description Gets a translation. The ID of the translation must be specified.
     * @tag PluginMultilingualism
     * @param int $translationId 
     * @param array $query
     *      - *$id* - int - required
     *          - The ID of the translation
     * @return array
     *      - *id* - integer
     *          - The ID of the translation
     *      - *pluginSetId* - integer
     *          - The ID of the plugin set
     *      - *pluginName* - string
     *          - The name of the plugin
     *      - *languageCode* - string
     *          - The code of the language
     *      - *fileName* - string
     *          - The file of the key
     *      - *key* - string
     *          - The translation key
     *      - *value* - string
     *          - The translation value
     */
    public function getLanguagesTranslationByTranslationId(int $translationId, array $query): array
    {
        return $this->api(array_merge(["/rest/languages/translations/{$translationId}"], $query));
    }
                
    /**
     * @description Updates a translation. The ID of the translation must be specified
     * @tag PluginMultilingualism
     * @param int $translationId 
     * @param array $query
     *      - *$id* - int - required
     *          - The ID of the translation
     *      - *$pluginSetId* - int - required
     *          - The ID of the plugin set
     *      - *$pluginName* - string - required
     *          - The name of the plugin
     *      - *$languageCode* - string - required
     *          - The language code for the translation
     *      - *$key* - string - required
     *          - The translation key
     *      - *$value* - string - required
     *          - The value of the translation
     *      - *$fileName* - string - required
     *          - The value of the translation
     * @return array
     *      - *id* - integer
     *          - The ID of the translation
     *      - *pluginSetId* - integer
     *          - The ID of the plugin set
     *      - *pluginName* - string
     *          - The name of the plugin
     *      - *languageCode* - string
     *          - The code of the language
     *      - *fileName* - string
     *          - The file of the key
     *      - *key* - string
     *          - The translation key
     *      - *value* - string
     *          - The translation value
     */
    public function updateLanguagesTranslationByTranslationId(int $translationId, array $query): array
    {
        return $this->api(array_merge(["/rest/languages/translations/{$translationId}"], $query), 'PUT');
    }
                    
    /**
     * @description Deletes multiple translation. The pluginSetId and languageCode must be specified.
     * @tag PluginMultilingualism
     * @param int $pluginSetId 
     * @param int $languageCode 
     * @param array $query
     *      - *$pluginSetId* - int - required
     *          - The ID of the plugin set
     *      - *$languageCode* - string - required
     *          - The code of the language
     */
    public function deletePluginSetsLanguageByPluginSetIdLanguageCode(int $pluginSetId, int $languageCode, array $query)
    {
        return $this->api(array_merge(["/rest/plugin_sets/{$pluginSetId}/languages/{$languageCode}"], $query), 'DELETE');
    }
    
}