<?php

namespace lujie\plentyMarkets\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The plentymarkets REST API expands the functionality of the plentymarkets CMS and allows access to resources, i.e. data records, via unique URI paths
*/
class Configuration extends \lujie\plentyMarkets\BasePlentyMarketsRestClient
{

                
    /**
     * @description Returns a json of the configuration from a specific plugin and plugin set
     * @tag Configuration
     * @param int $pluginSetId 
     */
    public function getPluginsPluginSetsConfigurationsExportByPluginSetId(int $pluginSetId)
    {
        return $this->api("/rest/plugins/plugin_sets/{$pluginSetId}/configurations/export");
    }
                    
    /**
     * @description Imports a previously exported configuration file for a specific plugin and plugin set.
     * @tag Configuration
     * @param int $pluginSetId 
     */
    public function createPluginsPluginSetsConfigurationsImportByPluginSetId(int $pluginSetId)
    {
        return $this->api("/rest/plugins/plugin_sets/{$pluginSetId}/configurations/import", 'POST');
    }
                    
    /**
     * @description Gets the configuration file for a specific plugin based on its ID and plugin set.
     * @tag Configuration
     * @param int $pluginId 
     * @param int $pluginSetId 
     */
    public function getPluginsPluginSetsConfigurationLayoutByPluginIdPluginSetId(int $pluginId, int $pluginSetId)
    {
        return $this->api("/rest/plugins/{$pluginId}/plugin_sets/{$pluginSetId}/configuration_layout");
    }
                    
    /**
     * @description Loads detailed configuration of a plugin based on its ID and plugin set ID.
     * @tag Configuration
     * @param int $pluginId 
     * @param int $pluginSetId 
     * @return array
     */
    public function getPluginsPluginSetsConfigurationsByPluginIdPluginSetId(int $pluginId, int $pluginSetId): array
    {
        return $this->api("/rest/plugins/{$pluginId}/plugin_sets/{$pluginSetId}/configurations");
    }
                
    /**
     * @description Saves the configuration file for a specific plugin based on its ID and the plugin set.
     * @tag Configuration
     * @param int $pluginId 
     * @param int $pluginSetId 
     */
    public function updatePluginsPluginSetsConfigurationByPluginIdPluginSetId(int $pluginId, int $pluginSetId)
    {
        return $this->api("/rest/plugins/{$pluginId}/plugin_sets/{$pluginSetId}/configurations", 'PUT');
    }
    
}
