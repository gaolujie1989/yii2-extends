<?php

namespace lujie\plentyMarkets\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The plentymarkets REST API expands the functionality of the plentymarkets CMS and allows access to resources, i.e. data records, via unique URI paths
*/
class PluginSet extends \lujie\plentyMarkets\BasePlentyMarketsRestClient
{

                
    /**
     * @description Lists all available sets.
     * @tag PluginSet
     * @return array
     */
    public function getPluginSets(): array
    {
        return $this->api("/rest/plugin_sets");
    }
                
    /**
     * @description Creates a new plugin set with the given name. If a 'copyPluginSetId' is given, all set entries from that set will be copied into the new set
     * @tag PluginSet
     * @param array $query
     *      - *copyPluginSetId* - int - optional
     *          - The ID of the plugin set of which to copy the set entries from into the
     * new set
     * @param array $data 
     * @return array
     *      - *id* - integer
     *      - *hash* - string
     *      - *hasSuccessfulBuild* - boolean
     *      - *parentPluginSetId* - integer
     *      - *name* - string
     *      - *description* - string
     */
    public function createPluginSet(array $query = [], array $data): array
    {
        return $this->api(array_merge(["/rest/plugin_sets"], $query), 'POST', $data);
    }
                    
    /**
     * @description Get details about plugin sets (count, limits)
     * @tag PluginSet

     */
    public function getPluginSetsInfo(): void
    {
        $this->api("/rest/plugin_sets/info");
    }
                    
    /**
     * @description Get the hash required to preview a plugin set. Response content will be in the form ['previewHash' => 'adf245o9nwu90sdfjw409u4'].
     * @tag PluginSet

     */
    public function getPluginSetsPreviewHash(): void
    {
        $this->api("/rest/plugin_sets/preview_hash");
    }
                    
    /**
     * @description Gets all the open source plugins from the Inbox.
     * @tag PluginSet
     * @return array
     */
    public function getPluginSetsS3InboxOpensourcePlugins(): array
    {
        return $this->api("/rest/plugin_sets/s3-inbox-opensource-plugins");
    }
                    
    /**
     * @description Lists all translations for all plugins in a plugin set
     * @tag PluginSet
     * @param int $pluginSetId 
     * @param array $query
     *      - *$pluginSetId* - int - required
     *          - The ID of the plugin set
     */
    public function getPluginSetsLanguagesByPluginSetId(int $pluginSetId, array $query): void
    {
        $this->api(array_merge(["/rest/plugin_sets/{$pluginSetId}/languages"], $query));
    }
                    
    /**
     * @description Lists all plugin translations as a CSV file.
     * @tag PluginSet
     * @param int $pluginSetId 
     * @param int $languageCode 
     * @param array $query
     *      - *$pluginSetId* - int - required
     *          - The ID of the plugin set
     *      - *$languageCode* - string - required
     *          - The code of the language
     */
    public function getPluginSetsLanguagesCsvByPluginSetIdLanguageCode(int $pluginSetId, int $languageCode, array $query): void
    {
        $this->api(array_merge(["/rest/plugin_sets/{$pluginSetId}/languages/csv/{$languageCode}"], $query));
    }
                    
    /**
     * @description Uploads to S3 and synchronizes language resources
     * @tag PluginSet
     * @param int $pluginSetId 
     */
    public function createPluginSetsLanguagesUploadTranslationByPluginSetId(int $pluginSetId): void
    {
        $this->api("/rest/plugin_sets/{$pluginSetId}/languages/upload_translations", 'POST');
    }
                    
    /**
     * @description Lists all plugin translations that have been merged.
     * @tag PluginSet
     * @param int $pluginSetId 
     * @param int $targetLanguage 
     * @param array $query
     *      - *$pluginSetId* - int - required
     *          - The ID of the plugin set
     *      - *$targetLanguage* - string - required
     *          - The code of the language we target
     */
    public function getPluginSetsLanguageByPluginSetIdTargetLanguage(int $pluginSetId, int $targetLanguage, array $query): void
    {
        $this->api(array_merge(["/rest/plugin_sets/{$pluginSetId}/languages/{$targetLanguage}"], $query));
    }
                
    /**
     * @description Update all plugin translations from a csv file
     * @tag PluginSet
     * @param int $pluginSetId 
     * @param int $targetLanguage 
     * @param array $query
     *      - *$pluginSetId* - int - required
     *          - The ID of the plugin set
     *      - *$targetLanguage* - string - required
     *          - The code of the language
     */
    public function createPluginSetsLanguageByPluginSetIdTargetLanguage(int $pluginSetId, int $targetLanguage, array $query): void
    {
        $this->api(array_merge(["/rest/plugin_sets/{$pluginSetId}/languages/{$targetLanguage}"], $query), 'POST');
    }
                    
    /**
     * @description Deletes a plugin set. Response content will be the number of sets deleted (i. e. '1' or '0').
     * @tag PluginSet
     * @param int $setId 
     */
    public function deletePluginSetBySetId(int $setId): void
    {
        $this->api("/rest/plugin_sets/{$setId}", 'DELETE');
    }
                
    /**
     * @description Gets a set based on its ID.
     * @tag PluginSet
     * @param int $setId 
     * @return array
     *      - *id* - integer
     *      - *hash* - string
     *      - *hasSuccessfulBuild* - boolean
     *      - *parentPluginSetId* - integer
     *      - *name* - string
     *      - *description* - string
     */
    public function getPluginSetBySetId(int $setId): array
    {
        return $this->api("/rest/plugin_sets/{$setId}");
    }
                
    /**
     * @description Updates a set. Response content will be 'true' if the update was successful, 'false' if not.
     * @tag PluginSet
     * @param int $setId 
     * @param array $data 
     */
    public function updatePluginSetBySetId(int $setId, array $data): void
    {
        $this->api("/rest/plugin_sets/{$setId}", 'PUT', $data);
    }
                    
    /**
     * @description Lists all active Plugins of given Set.
     * @tag PluginSet
     * @param int $setId 
     * @param array $query
     *      - *'includeStage* - boolean - optional
     *          - Include staged plugins in the result.
     */
    public function getPluginSetsPluginsBySetId(int $setId, array $query = []): void
    {
        $this->api(array_merge(["/rest/plugin_sets/{$setId}/plugins"], $query));
    }
                    
    /**
     * @description Checks if the plugin is compatible, based on its requirements.
     * @tag PluginSet
     * @param int $setId 
     * @param int $pluginName 
     * @param int $variationId 
     */
    public function getPluginSetsPluginsGetCompatibilityBySetIdPluginNameVariationId(int $setId, int $pluginName, int $variationId): void
    {
        $this->api("/rest/plugin_sets/{$setId}/plugins/get_compatibility/{$pluginName}/{$variationId}");
    }
                    
    /**
     * @description Searches for plugins. The search can be refined with numerous parameters.
     * @tag PluginSet
     * @param int $setId 
     * @param array $query
     *      - *pluginSetId* - int - optional
     *          - Search for plugins from a specific plugin set.
     *      - *name* - string - optional
     *          - Search for plugins with a specific name.
     *      - *in-stage* - boolean - optional
     *          - Search for plugins that are in stage.
     *      - *in-productive* - boolean - optional
     *          - Search for plugins that are in productive.
     *      - *type* - string - optional
     *          - Search for plugins of a given type. Available types are 'Template' and 'Export'.
     *      - *checkRequirements* - boolean - optional
     *          - Add the requirements to the response. This will add the 'notInstalledRequirements',
     * 'notActiveStageRequirements' and 'notActiveProductiveRequirements' fields to the returned plugin model(s).
     *      - *checkUpdate* - boolean - optional
     *          - Check for updates. If an update for a plugin is available, this will add the
     * 'updateInformation' field to the returned plugin model(s).
     *      - *source* - string - optional
     *          - Search for plugins from a specific source. Available sources are 'marketplace', 'git', and
     * 'local'.
     *      - *installed* - boolean - optional
     *          - Only search for installed / not installed plugins.
     *      - *active* - boolean - optional
     *          - Only search for plugins that are active / inactive.
     *      - *itemsPerPage* - int - optional
     *          - How many plugins to include per page of the search result.
     * @return array
     *      - *page* - integer
     *          - Current page of the response
     *      - *totalsCount* - integer
     *          - The total number of entries in the response
     *      - *isLastPage* - boolean
     *          - Flag that indicates if the page shown is the last page of the response
     *      - *lastPageNumber* - integer
     *          - The last page number
     *      - *firstOnPage* - integer
     *          - The index of the first item of the current page result
     *      - *lastOnPage* - integer
     *          - The index of the last item of the current page result
     *      - *itemsPerPage* - integer
     *          - The requested amount of items per result page
     *      - *entries* - array
     *          - List of Plugin
     */
    public function getPluginSetsPluginsSearchBySetId(int $setId, array $query = []): array
    {
        return $this->api(array_merge(["/rest/plugin_sets/{$setId}/plugins/search"], $query));
    }
                    
    /**
     * @description Removes a plugin from a set and deletes all plugin files. Response content will be 'true' if the deletion was successful,
'false' if not. If no plugin set with the given id can be found or the plugin is not associated to the set, a 404 will be returned.
     * @tag PluginSet
     * @param int $setId 
     * @param int $pluginId 
     */
    public function deletePluginSetsPluginBySetIdPluginId(int $setId, int $pluginId): void
    {
        $this->api("/rest/plugin_sets/{$setId}/plugins/{$pluginId}", 'DELETE');
    }
                
    /**
     * @description Adds a plugin to the set based on its ID and the plugin set's ID.
     * @tag PluginSet
     * @param int $setId 
     * @param int $pluginId 
     */
    public function createPluginSetsPluginBySetIdPluginId(int $setId, int $pluginId): void
    {
        $this->api("/rest/plugin_sets/{$setId}/plugins/{$pluginId}", 'POST');
    }
                
    /**
     * @description Activates / deactivates a plugin for a set by trashing or restoring the respective set entry. Both the plugin set's ID and the plugin's ID must be provided
     * @tag PluginSet
     * @param int $setId 
     * @param int $pluginId 
     */
    public function updatePluginSetsPluginBySetIdPluginId(int $setId, int $pluginId): void
    {
        $this->api("/rest/plugin_sets/{$setId}/plugins/{$pluginId}", 'PUT');
    }
                    
    /**
     * @description Installs a git plugin into a set. Response content will be in the form ['gitPluginInstalled' => 'true' / 'false'].
     * @tag PluginSet
     * @param int $setId 
     * @param int $pluginId 
     */
    public function createPluginSetsPluginsInstallGitPluginBySetIdPluginId(int $setId, int $pluginId): void
    {
        $this->api("/rest/plugin_sets/{$setId}/plugins/{$pluginId}/install_git_plugin", 'POST');
    }
                    
    /**
     * @description Changes the position of a plugin in a set.
     * @tag PluginSet
     * @param int $setId 
     * @param int $pluginId 
     * @return array
     *      - *id* - integer
     *      - *pluginId* - integer
     *      - *pluginSetId* - integer
     *      - *branchName* - string
     *      - *commit* - string
     *      - *position* - integer
     *      - *isInstalledFromGit* - boolean
     */
    public function createPluginSetsPluginsSetPositionBySetIdPluginId(int $setId, int $pluginId): array
    {
        return $this->api("/rest/plugin_sets/{$setId}/plugins/{$pluginId}/setPosition", 'POST');
    }
                    
    /**
     * @description Lists all the SetEntries of a specific set based on its ID.
     * @tag PluginSet
     * @param int $setId 
     * @return array
     */
    public function getPluginSetsSetEntriesBySetId(int $setId): array
    {
        return $this->api("/rest/plugin_sets/{$setId}/set_entries");
    }
                    
    /**
     * @description Gets info about a git plugin based on the plugin name.
     * @tag PluginSet
     * @param int $pluginName 
     * @return array
     */
    public function getPluginSetsNewGitPluginDetailByPluginName(int $pluginName): array
    {
        return $this->api("/rest/plugin_sets_new/git_plugin_details/{$pluginName}");
    }
                    
    /**
     * @description Gets info about the plugin, based on the plugin name.
     * @tag PluginSet
     * @param int $pluginName 
     * @param int $variationId 
     * @return array
     */
    public function getPluginSetsNewPluginDetailByPluginNameVariationId(int $pluginName, int $variationId): array
    {
        return $this->api("/rest/plugin_sets_new/plugin_details/{$pluginName}/{$variationId}");
    }
                    
    /**
     * @description Lists all active Plugins of given Set.
     * @tag PluginSet
     * @param int $pluginSetId 
     * @param array $query
     *      - *'includeStage* - boolean - optional
     *          - Include staged plugins in the result.
     */
    public function getPluginsPluginSetsPluginsByPluginSetId(int $pluginSetId, array $query = []): void
    {
        $this->api(array_merge(["/rest/plugins/plugin_sets/{$pluginSetId}/plugins"], $query));
    }
                    
    /**
     * @description Lists all containers of plugins in a set
     * @tag PluginSet
     * @param int $pluginId 
     * @param int $pluginSetId 
     */
    public function getPluginsPluginSetsContainersByPluginIdPluginSetId(int $pluginId, int $pluginSetId): void
    {
        $this->api("/rest/plugins/{$pluginId}/plugin_sets/{$pluginSetId}/containers");
    }
    
}
