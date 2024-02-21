<?php

namespace lujie\plentyMarkets\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The plentymarkets REST API expands the functionality of the plentymarkets CMS and allows access to resources, i.e. data records, via unique URI paths
*/
class Cloud extends \lujie\plentyMarkets\BasePlentyMarketsRestClient
{

                
    /**
     * @description Remove a single object from frontend storage.
     * @tag Cloud
     * @param array $query
     *      - *key* - string - required
     *          - The key of the object to delete.
     */
    public function deleteStorageFrontendFile(array $query)
    {
        return $this->api(array_merge(["/rest/storage/frontend/file"], $query), 'DELETE');
    }
                
    /**
     * @description Get file information for a single object in frontend storage. Append public cloudfront url to retrieved object.
     * @tag Cloud
     * @param array $query
     *      - *key* - string - required
     *          - The key of the object to get information about.
     * @return array
     */
    public function getStorageFrontendFile(array $query): array
    {
        return $this->api(array_merge(["/rest/storage/frontend/file"], $query));
    }
                
    /**
     * @description If file is an image, generate a thumbnail and store dimensions in metadata.
     * @tag Cloud
     * @param array $query
     *      - *key* - string - required
     *          - The key for the uploaded object.
     *      - *maxAge* - int - optional
     *          - Number of seconds until the content of the file expires.
     * @return array
     */
    public function createStorageFrontendFile(array $query): array
    {
        return $this->api(array_merge(["/rest/storage/frontend/file"], $query), 'POST');
    }
                
    /**
     * @description If file is an image, generate a thumbnail and store dimensions in metadata.
     * @tag Cloud
     * @param array $query
     *      - *sourceKey* - string - required
     *          - The key of the object to rename.
     *      - *targetKey* - string - required
     *          - The new key to store the object at.
     * @return array
     */
    public function updateStorageFrontendFile(array $query): array
    {
        return $this->api(array_merge(["/rest/storage/frontend/file"], $query), 'PUT');
    }
                    
    /**
     * @description Get assigned metadata for a single storage object
     * @tag Cloud
     * @param array $query
     *      - *key* - string - required
     *          - The key of the object to get metadata for.
     * @return array
     */
    public function getStorageFrontendFileMetadata(array $query): array
    {
        return $this->api(array_merge(["/rest/storage/frontend/file/metadata"], $query));
    }
                
    /**
     * @description Update metadata of an storage object.
     * @tag Cloud
     * @param array $query
     *      - *key* - string - required
     *          - The key of the object to update metadata for.
     *      - *metadata* - array - required
     *          - The metadata to assign to storage object
     * @return array
     */
    public function createStorageFrontendFileMetadatum(array $query): array
    {
        return $this->api(array_merge(["/rest/storage/frontend/file/metadata"], $query), 'POST');
    }
                    
    /**
     * @description Deletes a list of files from frontend storage. A list of storage keys must be specified.
     * @tag Cloud
     * @param array $query
     *      - *keyList* - array - required
     *          - List of storage keys for the files to be deleted.
     */
    public function deleteStorageFrontendFile(array $query)
    {
        return $this->api(array_merge(["/rest/storage/frontend/files"], $query), 'DELETE');
    }
                
    /**
     * @description List files from frontend storage. Append public cloudfront url to each retrieved object.
     * @tag Cloud
     * @param array $query
     *      - *continuationToken* - string - optional
     *          - The <code>continuationToken</code> of a previous request to continue listing objects with.
     * @return array
     */
    public function getStorageFrontendFiles(array $query = []): array
    {
        return $this->api(array_merge(["/rest/storage/frontend/files"], $query));
    }
                    
    /**
     * @description Gets the URL of a layout document. The storage key must be specified. The returned URL expires after 10 minutes.
     * @tag Cloud
     * @param array $query
     *      - *key* - string - required
     *          - The storage key for the frontend document to retrieve the URL for. Include the storage key in the request in a <code>key</code> field.
     */
    public function getStorageFrontendObjectUrl(array $query)
    {
        return $this->api(array_merge(["/rest/storage/frontend/object-url"], $query));
    }
                    
    /**
     * @description Deletes a list of layout documents from storage. A list of storage keys must be specified.
     * @tag Cloud
     * @param array $query
     *      - *keyList* - array - required
     *          - List of storage keys for the files to be deleted.
     */
    public function deleteStorageLayout(array $query)
    {
        return $this->api(array_merge(["/rest/storage/layout"], $query), 'DELETE');
    }
                
    /**
     * @description Uploads a layout document to storage. The storage key (i.e. file path) must be specified.
     * @tag Cloud
     * @param array $query
     *      - *key* - string - required
     *          - The storage key for the layout document to upload. Include the storage key (i.e. file path) in the request in a <code>key</code> field.
     * @return array
     *      - *key* - string
     *      - *lastModified* - string
     *      - *metaData* - array
     *      - *eTag* - string
     *      - *size* - integer
     *      - *storageClass* - string
     *      - *body* - string
     *      - *contentType* - string
     *      - *contentLength* - string
     */
    public function createStorageLayout(array $query): array
    {
        return $this->api(array_merge(["/rest/storage/layout"], $query), 'POST');
    }
                    
    /**
     * @description Lists up to 1000 layout documents per request. If more than 1000 layout documents are available,
a <code>nextContinuationToken</code> is returned. Use this token to get the next (up to) 1000 layout documents.
Use the same request and include a field with the key <code>continuationToken</code> as well as the returned
token from the previous call as the value.

Check the <code>isTruncated</code> field in the response to see if more results are available. If <code>isTruncated</code> is true,
repeat the request using the token from the <code>nextContinuationToken</code> field of the previous response to get all
results.
     * @tag Cloud
     * @param array $query
     *      - *continuationToken* - string - optional
     *          - Token for listing the next (up to) 1000 layout documents.
     * @return array
     *      - *isTruncated* - boolean
     *      - *nextContinuationToken* - string
     *      - *commonPrefixes* - array
     */
    public function getStorageLayoutList(array $query = []): array
    {
        return $this->api(array_merge(["/rest/storage/layout/list"], $query));
    }
                    
    /**
     * @description Gets the URL of a layout document. The storage key must be specified. The returned URL expires after 10 minutes.
     * @tag Cloud
     * @param array $query
     *      - *key* - string - required
     *          - The storage key for the layout document to retrieve the URL for. Include the storage key in the request in a <code>key</code> field.
     */
    public function getStorageLayoutObjectUrl(array $query)
    {
        return $this->api(array_merge(["/rest/storage/layout/object-url"], $query));
    }
                    
    /**
     * @description Gets the URL of a order property file. The storage key must be specified. The returned URL expires after 10
minutes.
     * @tag Cloud
     * @param array $query
     *      - *key* - string - required
     *          - The storage key for the order property
     *                        file to retrieve the URL for. Include the storage key in the request in a
     *                        <code>key</code> field.
     */
    public function getStorageOrderPropertiesObjectUrl(array $query)
    {
        return $this->api(array_merge(["/rest/storage/order-properties/object-url"], $query));
    }
                    
    /**
     * @description Deletes a list of files from the inbox. A list of storage keys must be specified.
     * @tag Cloud
     * @param array $query
     *      - *keyList* - array - required
     *          - List of storage keys for the files to be deleted.
     */
    public function deleteStoragePluginsInbox(array $query)
    {
        return $this->api(array_merge(["/rest/storage/plugins/inbox"], $query), 'DELETE');
    }
                
    /**
     * @description Uploads a file to the inbox. The storage key (i.e. file path) must be specified.
     * @tag Cloud
     * @param array $query
     *      - *key* - string - required
     *          - The storage key for the file to upload. Include the storage key in the request in a <code>key</code> field.
     * @return array
     *      - *key* - string
     *      - *lastModified* - string
     *      - *metaData* - array
     *      - *eTag* - string
     *      - *size* - integer
     *      - *storageClass* - string
     *      - *body* - string
     *      - *contentType* - string
     *      - *contentLength* - string
     */
    public function createStoragePluginsInbox(array $query): array
    {
        return $this->api(array_merge(["/rest/storage/plugins/inbox"], $query), 'POST');
    }
                    
    /**
     * @description Commits all plugin changes.
     * @tag Cloud
     * @return array
     */
    public function createStoragePluginsInboxCommit(): array
    {
        return $this->api("/rest/storage/plugins/inbox/commit", 'POST');
    }
                    
    /**
     * @description Lists all files of all plugins stored in the inbox. A prefix can be specified to list all files of a specific
plugin folder only.
     * @tag Cloud
     * @param array $query
     *      - *prefix* - string - optional
     *          - Prefix to list all files of a specific plugin folder only. The prefix also means the plugin path. The <code>prefix</code> key with the value <code>PluginA/src/</code> will only return files in the <b>src</b> folder of <b>PluginA</b>.
     * @return array
     *      - *isTruncated* - boolean
     *      - *nextContinuationToken* - string
     *      - *commonPrefixes* - array
     */
    public function getStoragePluginsInboxList(array $query = []): array
    {
        return $this->api(array_merge(["/rest/storage/plugins/inbox/list"], $query));
    }
                    
    /**
     * @description Gets the content of a file stored in the inbox. The storage key (i.e. file path) must be specified.
     * @tag Cloud
     * @param array $query
     *      - *key* - string - optional
     *          - The storage key for the file to retrieve. Include the storage key in the request in a <code>key</code> field.
     */
    public function getStoragePluginsInboxObjectUrl(array $query = [])
    {
        return $this->api(array_merge(["/rest/storage/plugins/inbox/object-url"], $query));
    }
                    
    /**
     * @description Get the cloud metrics for this system
     * @tag Cloud
     * @return array
     *      - *page* - integer
     *      - *totalsCount* - integer
     *      - *isLastPage* - boolean
     */
    public function getSystemCloudMetrics(): array
    {
        return $this->api("/rest/system/cloud/metrics");
    }
                    
    /**
     * @description Supply usage data for given plentymarkets system
     * @tag Cloud
     * @param int $plentyId 
     * @param int $date 
     */
    public function getSystemMetricByPlentyIdDate(int $plentyId, int $date)
    {
        return $this->api("/rest/system/metrics/{$plentyId}/{$date}");
    }
    
}
