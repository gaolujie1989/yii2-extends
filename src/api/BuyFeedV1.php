<?php

namespace lujie\ebay\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Feed API provides the ability to download TSV_GZIP feed files containing eBay items and an hourly snapshot file for a specific category, date, and marketplace.<br /><br />In addition to the API, there is an open-source Feed SDK written in Java that downloads, combines files into a single file when needed, and unzips the entire feed file. It also lets you specify field filters to curate the items in the file.
*/
class BuyFeedV1 extends \lujie\ebay\BaseEbayRestClient
{

    public $apiBaseUrl = 'https://api.ebay.com/buy/feed/v1';

                
    /**
    * @description The getAccess method retrieves the access rules specific to the application; for example, the feed types to which the application has permissions. An application may be constrained to certain marketplaces, and to specific L1 categories within those marketplaces. You can use this information to apply filters to the getFiles method when obtaining details on accessible downloadable files.<h3><b>Restrictions </b></h3>                <p>For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/overview.html#API">API Restrictions</a>.</p>
    * @tag access
    * @return array
    *      - *accesses* - array
    *          - The response payload will contain a list of every feed type the application can access. The marketplaces and L1 categories to which the application is constrained within each feed are also returned. If no marketplaces are listed for a particular feed type, the application has access to all marketplaces. L1 categories are constrained according to marketplace. If a marketplace is listed with no L1 categories, the application has access to all categories in that marketplace. See <b>Sample 1: getAccess Request</b> below.
    */
    public function getAccess(): array
    {
        return $this->api("/access");
    }
                    
    /**
    * @description Use the <b>getFeedType</b> method to obtain the details about a particular feed type to determine its applicability to your needs.<br /><br />With the response, you can compare the eBay marketplaces and categories with the eBay marketplaces and categories that your application is enabled to access. By making these comparisons, you can avoid attempting to download feed files that you do not have access to.<br /><br /><span class="tablenote"><span style="color:#004680"><strong>Note:</strong></span> For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/overview.html#API">API Restrictions</a>.</span>
    * @tag feed_type
    * @param string $feedTypeId The unique identifier for the feed type to be used as a search filter.<br /><br />Use the <b>getFeedTypes</b> method to identify the available feed types.<br /><br /><span class="tablenote"><span style="color:#004680"><strong>Note:</strong></span> Refer to <a href="/api-docs/buy/feed/v1/static/overview.html#feed-types" target="_blank">Supported feed types</a> to learn more about the feed types supported by the Feed API.</span>
    * @return array
    *      - *description* - string
    *          - A description of the feed type.
    *      - *feedTypeId* - string
    *          - The unique identifier of the feed type.<br /><br /><span class="tablenote"><span style="color:#004680"><strong>Note:</strong></span> Refer to <a href="/api-docs/buy/feed/v1/static/overview.html#feed-types" target="_blank">Supported feed types</a> for additional details.</span>
    *      - *supportedFeeds* - array
    *          - An array of the feed files of the indicated feed type that are available to be downloaded.
    */
    public function getFeedType(string $feedTypeId): array
    {
        return $this->api("/feed_type/{$feedTypeId}");
    }
                
    /**
    * @description <p>Use the <b>getFeedTypes</b> method to obtain the details about one or more feed types that are available to be downloaded. If no query parameters are used, all possible feed types are returned.</p>You can filter your search by adding <b>feed_scope</b> and/or <b>marketplace_ids</b> parameters to the URI.</p><p>For instance, a call using <code>GET https://api.ebay.com/buy/feed/v1/feed_type</code> will return all available feed files. A call using <code> GET https://api.ebay.com/buy/feed/v1/feed_type?feed_scope=DAILY&marketplace_ids=EBAY_US</code> will limit the returned list to daily feed files available from the  US marketplace.</p><h3><b>Restrictions </b></h3><p>For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/v1/overview.html#API">API Restrictions</a>.</p>
    * @tag feed_type
    * @param array $query
    *      - *continuation_token* - string - optional
    *          - The server returns this token to the web client when the responses received require multiple pages to display. The web client sends this token back to the server to get the next page of results.
    *      - *feed_scope* - string - optional
    *          - Specifies the frequency with which the feed file is made available (<code>HOURLY</code>, <code>DAILY</code>, <code>WEEKLY</code>).<br /><br />Currently only <code>DAILY</code> is supported.
    *      - *limit* - string - optional
    *          - The number of records to show in the current response.<br /><br /><b>Default:</b> 20<br /><b>Minimum:</b> 20<br /><b>Maximum:</b> 100
    *      - *marketplace_ids* - string - optional
    *          - Use this parameter to limit marketplaces you want to see. To search for multiple marketplaces at once, list them in the URI separated by commas.<br /><br /><b>Example:</b> <code>GET https://api.ebay.com/buy/feed/v1/feedtype?marketplaceids=EBAY_FR,EBAY_AU</code>.<br /><br />See <a href="/api-docs/buy/feed/v1/overview.html#API">API Restrictions</a> for information on supported sites.
    * @return Iterator
    *      - *feedTypes* - array
    *          - An array of the feed types that match the search criteria.
    *      - *href* - string
    *          - The URL to to the current set of results.
    *      - *limit* - integer
    *          - The number of records to show in the current response.
    *      - *next* - string
    *          - You can use this URL to retrieve the next page of results beyond those displayed on the page if there are more results that match the search criteria.
    *      - *total* - integer
    *          - The total number of matches for the search criteria.
    */
    public function eachFeedTypes(array $query): Iterator
    {
        return $this->eachInternal('getFeedTypes', func_get_args());
    }
        
    /**
    * @description <p>Use the <b>getFeedTypes</b> method to obtain the details about one or more feed types that are available to be downloaded. If no query parameters are used, all possible feed types are returned.</p>You can filter your search by adding <b>feed_scope</b> and/or <b>marketplace_ids</b> parameters to the URI.</p><p>For instance, a call using <code>GET https://api.ebay.com/buy/feed/v1/feed_type</code> will return all available feed files. A call using <code> GET https://api.ebay.com/buy/feed/v1/feed_type?feed_scope=DAILY&marketplace_ids=EBAY_US</code> will limit the returned list to daily feed files available from the  US marketplace.</p><h3><b>Restrictions </b></h3><p>For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/v1/overview.html#API">API Restrictions</a>.</p>
    * @tag feed_type
    * @param array $query
    *      - *continuation_token* - string - optional
    *          - The server returns this token to the web client when the responses received require multiple pages to display. The web client sends this token back to the server to get the next page of results.
    *      - *feed_scope* - string - optional
    *          - Specifies the frequency with which the feed file is made available (<code>HOURLY</code>, <code>DAILY</code>, <code>WEEKLY</code>).<br /><br />Currently only <code>DAILY</code> is supported.
    *      - *limit* - string - optional
    *          - The number of records to show in the current response.<br /><br /><b>Default:</b> 20<br /><b>Minimum:</b> 20<br /><b>Maximum:</b> 100
    *      - *marketplace_ids* - string - optional
    *          - Use this parameter to limit marketplaces you want to see. To search for multiple marketplaces at once, list them in the URI separated by commas.<br /><br /><b>Example:</b> <code>GET https://api.ebay.com/buy/feed/v1/feedtype?marketplaceids=EBAY_FR,EBAY_AU</code>.<br /><br />See <a href="/api-docs/buy/feed/v1/overview.html#API">API Restrictions</a> for information on supported sites.
    * @return Iterator
    *      - *feedTypes* - array
    *          - An array of the feed types that match the search criteria.
    *      - *href* - string
    *          - The URL to to the current set of results.
    *      - *limit* - integer
    *          - The number of records to show in the current response.
    *      - *next* - string
    *          - You can use this URL to retrieve the next page of results beyond those displayed on the page if there are more results that match the search criteria.
    *      - *total* - integer
    *          - The total number of matches for the search criteria.
    */
    public function batchFeedTypes(array $query): Iterator
    {
        return $this->batchInternal('getFeedTypes', func_get_args());
    }
    
    /**
    * @description <p>Use the <b>getFeedTypes</b> method to obtain the details about one or more feed types that are available to be downloaded. If no query parameters are used, all possible feed types are returned.</p>You can filter your search by adding <b>feed_scope</b> and/or <b>marketplace_ids</b> parameters to the URI.</p><p>For instance, a call using <code>GET https://api.ebay.com/buy/feed/v1/feed_type</code> will return all available feed files. A call using <code> GET https://api.ebay.com/buy/feed/v1/feed_type?feed_scope=DAILY&marketplace_ids=EBAY_US</code> will limit the returned list to daily feed files available from the  US marketplace.</p><h3><b>Restrictions </b></h3><p>For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/v1/overview.html#API">API Restrictions</a>.</p>
    * @tag feed_type
    * @param array $query
    *      - *continuation_token* - string - optional
    *          - The server returns this token to the web client when the responses received require multiple pages to display. The web client sends this token back to the server to get the next page of results.
    *      - *feed_scope* - string - optional
    *          - Specifies the frequency with which the feed file is made available (<code>HOURLY</code>, <code>DAILY</code>, <code>WEEKLY</code>).<br /><br />Currently only <code>DAILY</code> is supported.
    *      - *limit* - string - optional
    *          - The number of records to show in the current response.<br /><br /><b>Default:</b> 20<br /><b>Minimum:</b> 20<br /><b>Maximum:</b> 100
    *      - *marketplace_ids* - string - optional
    *          - Use this parameter to limit marketplaces you want to see. To search for multiple marketplaces at once, list them in the URI separated by commas.<br /><br /><b>Example:</b> <code>GET https://api.ebay.com/buy/feed/v1/feedtype?marketplaceids=EBAY_FR,EBAY_AU</code>.<br /><br />See <a href="/api-docs/buy/feed/v1/overview.html#API">API Restrictions</a> for information on supported sites.
    * @return array
    *      - *feedTypes* - array
    *          - An array of the feed types that match the search criteria.
    *      - *href* - string
    *          - The URL to to the current set of results.
    *      - *limit* - integer
    *          - The number of records to show in the current response.
    *      - *next* - string
    *          - You can use this URL to retrieve the next page of results beyond those displayed on the page if there are more results that match the search criteria.
    *      - *total* - integer
    *          - The total number of matches for the search criteria.
    */
    public function getFeedTypes(array $query): array
    {
        return $this->api(array_merge(["/feed_type"], $query));
    }
                    
    /**
    * @description <p>Use the <b>downloadFile</b> method to download a selected TSV_gzip feed file.<p>Use the <b>getFiles</b> methods to obtain the <b>file_id</b> of the specific feed file you require.</p><h3><b>Downloading feed files </b></h3>  <p>The feed files are binary gzip files. If the file is larger than 200 MB, the download must be streamed in chunks. You specify the size of the chunks in bytes using the <a href="#range-header">Range</a> request header. The <a href="#content-range">content-range</a> response header indicates where in the full resource this partial chunk of data belongs  and the total number of bytes in the file.       For more information about using these headers, see <a href="/api-docs/buy/static/api-feed.html#retrv-gzip">Retrieving a GZIP feed file</a>.    </p>
    * @tag file
    * @param string $fileId The unique identifier of the feed file that you wish to download. Use the <b>getFiles</b> method to obtain the <b>fileId</b> value for the desired feed file.
    * @param array $headers
    *      - *Range* - string - optional
    *          - Indicates where in the full resource this partial chunk of data belongs and the total number of bytes in the file.<br /><br /><b>Example: </b> <code>bytes=0-102400</code>.<br /><br />For more information about using this header, see <a href="/api-docs/buy/static/api-feed.html#retrv-gzip">Retrieving a gzip feed file</a>.
    *      - *X-EBAY-C-MARKETPLACE-ID* - string - required
    *          - This is the ID of the eBay marketplace that the feed file belongs to. <br /><br /><b>Example:</b><code>X-EBAY-C-MARKETPLACE-ID: EBAY_US</code>.<br /><br />For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/overview.html#API">API Restrictions</a>.
    * @return array
    */
    public function downloadFile(string $fileId, array $headers): array
    {
        return $this->api("/file/{$fileId}/download", 'GET', [], $headers);
    }
                    
    /**
    * @description Use the <b>getFile</b> method to fetch the details of a feed file available to download, as specified by the file's <b>file_id</b>.</p><p>Details in the response include: the feed's <b>file_id</b>, the date it became available, eBay categories that support the feed, its frequency, the time span it covers, its feed type, its format (currently only TSV is available), its size in bytes, the schema under which it was pulled, and the marketplaces it applies to.</p>
    * @tag file
    * @param string $fileId Unique identifier of feed file. Feed file IDs can be retrieved with the <b>getFiles</b> method.
    * @param array $headers
    *      - *X-EBAY-C-MARKETPLACE-ID* - string - required
    *          - This is the ID of the eBay marketplace on which the feed file exists. <br /><br /><b>Example:</b> <code>X-EBAY-C-MARKETPLACE-ID: EBAY_US</code>.<br /><br />For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/overview.html#API">API Restrictions</a>.
    * @return array
    *      - *access* - string
    *          - Indicates whether the application is permitted to access the feed file. One of <code>ALLOWED</code> or <code>RESTRICTED</code>. For implementation help, refer to <a href='https://developer.ebay.com/api-docs/buy/feed/types/api:AccessEnum'>eBay API documentation</a>
    *      - *dimensions* - array
    *          - An array of dimensions supported by the corresponding feed file. <br /><br />Currently the only dimension available is <b>CATEGORY</b>.<br /><br /><b>Example:</b><BR /><code>&quot;dimensionKey&quot;: &quot;CATEGORY&quot;,<br />&quotvalues&quot;: &lsqb;&quot;15032&quot;&rsqb;
    *      - *feedDate* - string
    *          - The date on which the feed was created. <br /><br /><b>Format:</b> UTC format <code>(yyyy-MM-ddThh:00:00.000Z)</code>.
    *      - *feedScope* - string
    *          - Specifies the frequency with which the feed file is made available (<code>HOURLY</code>, <code>DAILY</code>, <code>WEEKLY</code>).<br /><br />Currently only <code>DAILY</code> is supported. For implementation help, refer to <a href='https://developer.ebay.com/api-docs/buy/feed/types/api:FeedScopeEnum'>eBay API documentation</a>
    *      - *feedTypeId* - string
    *          - The unique identifier of the feed type.<br /><br /><span class="tablenote"><span style="color:#004680"><strong>Note:</strong></span> Refer to <a href="/api-docs/buy/feed/v1/static/overview.html#feed-types" target="_blank">Supported feed types</a> for additional details.</span>
    *      - *fileId* - string
    *          - The file's unique identifier. This <b>fileid</b> is used to select the feed file when using the <b>downloadFile</b> method.
    *      - *format* - string
    *          - Format of the returned feed file. Currently only TSV is supported. For implementation help, refer to <a href='https://developer.ebay.com/api-docs/buy/feed/types/api:FormatEnum'>eBay API documentation</a>
    *      - *marketplaceId* - string
    *          - The eBay marketplace identifier for the marketplace(s) to which the feed applies.<br /><br /><b>Example:</b> <code>EBAY_UK</code>. For implementation help, refer to <a href='https://developer.ebay.com/api-docs/buy/feed/types/bas:MarketplaceIdEnum'>eBay API documentation</a>
    *      - *schemaVersion* - string
    *          - Version of the API schema under which the feed was created.
    *      - *size* - integer
    *          - Size of the feed file in bytes.
    *      - *span* - 
    *          - The time span between feed files that applies to the feed type (e.g., hourly, daily, weekly). This is returned in hours. <br /><br /><b>Possible Values: </b> <code>YEAR</code>, <code>MONTH</code>, <code>DAY</code>, <code>HOUR</code>
    */
    public function getFile(string $fileId, array $headers): array
    {
        return $this->api("/file/{$fileId}", 'GET', [], $headers);
    }
                
    /**
    * @description <p>The <b>getFiles</b> method provides a list of the feed files available for download.</p>Details for each feed returned include the date the feed was generated, the frequency with which it is pulled, its feed type, its <b>fileid</b>, its format (currently only TSV is supported), the eBay marketplaces it applies to, the schema version under which it was generated, its size in bytes, and the time span it covers (in hours).</p><p>You can limit your search results by feed type, marketplace, scope, and eBay L1 category.</p><h3><b>Restrictions </b></h3><p>For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/overview.html#API">API Restrictions</a>.</p>
    * @tag file
    * @param array $query
    *      - *category_ids* - string - optional
    *          - This query parameter is used to specify one or more eBay L1 category IDs. If this filter is used, only feed files that are supported for the specified category (or categories) will be returned in the response. Each category ID value must be delimited by a comma.<br /><br /><b>Max:</b> 20 values
    *      - *continuation_token* - string - optional
    *          - The server returns this token to the web client when the responses received require multiple pages to display. The web client sends this token back to the server to get the next page of results.
    *      - *feed_scope* - string - optional
    *          - Specifies the frequency with which the feed file is made available (<code>HOURLY</code>, <code>DAILY</code>, <code>WEEKLY</code>).<br /><br />Currently only <code>DAILY</code> is supported.
    *      - *feed_type_id* - string - optional
    *          - The unique identifier for the feed type.<br /><br />Use the <b>getFeedTypes</b> method to identify the available feed types.<br /><br /><span class="tablenote"><span style="color:#004680"><strong>Note:</strong></span> Refer to <a href="/api-docs/buy/feed/v1/static/overview.html#feed-types" target="_blank">Supported feed types</a> to learn more about the feed types supported by the Feed API.</span>
    *      - *limit* - string - optional
    *          - The number of records to show in the response.<br /><br /><b>Default:</b> 20<br /><br /><b>Minimum:</b> 20<br /><br /><b>Maximum:</b> 100
    *      - *look_back* - string - optional
    *          - How far back from the current time to limit the returned feed files. The returned feed files will be those generated between the current time and the look-back time.<br /><br /><b>Example:</b> A value of <code>120</code> will limit the returned feed files to those generated in the past 2 hours (120 minutes). If 3 feed files have been generated in the past 2 hours, those 3 files will be returned. A feed file generated 4 hours earlier will not be returned.
    * @param array $headers
    *      - *X-EBAY-C-MARKETPLACE-ID* - string - required
    *          - This is the ID of the eBay marketplace on which to search for feed files.<br /><br /><b>Example:</b> <code>X-EBAY-C-MARKETPLACE-ID: EBAY_US</code>.<br /><br /><p>For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/overview.html#API">API Restrictions</a>.
    * @return Iterator
    *      - *fileMetadata* - array
    *          - An array of metadata values describing the available feed files that match the input criteria.
    *      - *href* - string
    *          - The URL to to the current set of results.
    *      - *limit* - integer
    *          - The number of results that will be displayed on each page, as set by the <b>limit</b> URI parameter.<br /><br /><b>Default:</b> 20
    *      - *next* - string
    *          - You can use this URL to retrieve the next page of results beyond those displayed on the page if there are more results that match the search criteria.
    *      - *total* - integer
    *          - The total number of matches for the search criteria.
    */
    public function eachFiles(array $query, array $headers): Iterator
    {
        return $this->eachInternal('getFiles', func_get_args());
    }
        
    /**
    * @description <p>The <b>getFiles</b> method provides a list of the feed files available for download.</p>Details for each feed returned include the date the feed was generated, the frequency with which it is pulled, its feed type, its <b>fileid</b>, its format (currently only TSV is supported), the eBay marketplaces it applies to, the schema version under which it was generated, its size in bytes, and the time span it covers (in hours).</p><p>You can limit your search results by feed type, marketplace, scope, and eBay L1 category.</p><h3><b>Restrictions </b></h3><p>For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/overview.html#API">API Restrictions</a>.</p>
    * @tag file
    * @param array $query
    *      - *category_ids* - string - optional
    *          - This query parameter is used to specify one or more eBay L1 category IDs. If this filter is used, only feed files that are supported for the specified category (or categories) will be returned in the response. Each category ID value must be delimited by a comma.<br /><br /><b>Max:</b> 20 values
    *      - *continuation_token* - string - optional
    *          - The server returns this token to the web client when the responses received require multiple pages to display. The web client sends this token back to the server to get the next page of results.
    *      - *feed_scope* - string - optional
    *          - Specifies the frequency with which the feed file is made available (<code>HOURLY</code>, <code>DAILY</code>, <code>WEEKLY</code>).<br /><br />Currently only <code>DAILY</code> is supported.
    *      - *feed_type_id* - string - optional
    *          - The unique identifier for the feed type.<br /><br />Use the <b>getFeedTypes</b> method to identify the available feed types.<br /><br /><span class="tablenote"><span style="color:#004680"><strong>Note:</strong></span> Refer to <a href="/api-docs/buy/feed/v1/static/overview.html#feed-types" target="_blank">Supported feed types</a> to learn more about the feed types supported by the Feed API.</span>
    *      - *limit* - string - optional
    *          - The number of records to show in the response.<br /><br /><b>Default:</b> 20<br /><br /><b>Minimum:</b> 20<br /><br /><b>Maximum:</b> 100
    *      - *look_back* - string - optional
    *          - How far back from the current time to limit the returned feed files. The returned feed files will be those generated between the current time and the look-back time.<br /><br /><b>Example:</b> A value of <code>120</code> will limit the returned feed files to those generated in the past 2 hours (120 minutes). If 3 feed files have been generated in the past 2 hours, those 3 files will be returned. A feed file generated 4 hours earlier will not be returned.
    * @param array $headers
    *      - *X-EBAY-C-MARKETPLACE-ID* - string - required
    *          - This is the ID of the eBay marketplace on which to search for feed files.<br /><br /><b>Example:</b> <code>X-EBAY-C-MARKETPLACE-ID: EBAY_US</code>.<br /><br /><p>For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/overview.html#API">API Restrictions</a>.
    * @return Iterator
    *      - *fileMetadata* - array
    *          - An array of metadata values describing the available feed files that match the input criteria.
    *      - *href* - string
    *          - The URL to to the current set of results.
    *      - *limit* - integer
    *          - The number of results that will be displayed on each page, as set by the <b>limit</b> URI parameter.<br /><br /><b>Default:</b> 20
    *      - *next* - string
    *          - You can use this URL to retrieve the next page of results beyond those displayed on the page if there are more results that match the search criteria.
    *      - *total* - integer
    *          - The total number of matches for the search criteria.
    */
    public function batchFiles(array $query, array $headers): Iterator
    {
        return $this->batchInternal('getFiles', func_get_args());
    }
    
    /**
    * @description <p>The <b>getFiles</b> method provides a list of the feed files available for download.</p>Details for each feed returned include the date the feed was generated, the frequency with which it is pulled, its feed type, its <b>fileid</b>, its format (currently only TSV is supported), the eBay marketplaces it applies to, the schema version under which it was generated, its size in bytes, and the time span it covers (in hours).</p><p>You can limit your search results by feed type, marketplace, scope, and eBay L1 category.</p><h3><b>Restrictions </b></h3><p>For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/overview.html#API">API Restrictions</a>.</p>
    * @tag file
    * @param array $query
    *      - *category_ids* - string - optional
    *          - This query parameter is used to specify one or more eBay L1 category IDs. If this filter is used, only feed files that are supported for the specified category (or categories) will be returned in the response. Each category ID value must be delimited by a comma.<br /><br /><b>Max:</b> 20 values
    *      - *continuation_token* - string - optional
    *          - The server returns this token to the web client when the responses received require multiple pages to display. The web client sends this token back to the server to get the next page of results.
    *      - *feed_scope* - string - optional
    *          - Specifies the frequency with which the feed file is made available (<code>HOURLY</code>, <code>DAILY</code>, <code>WEEKLY</code>).<br /><br />Currently only <code>DAILY</code> is supported.
    *      - *feed_type_id* - string - optional
    *          - The unique identifier for the feed type.<br /><br />Use the <b>getFeedTypes</b> method to identify the available feed types.<br /><br /><span class="tablenote"><span style="color:#004680"><strong>Note:</strong></span> Refer to <a href="/api-docs/buy/feed/v1/static/overview.html#feed-types" target="_blank">Supported feed types</a> to learn more about the feed types supported by the Feed API.</span>
    *      - *limit* - string - optional
    *          - The number of records to show in the response.<br /><br /><b>Default:</b> 20<br /><br /><b>Minimum:</b> 20<br /><br /><b>Maximum:</b> 100
    *      - *look_back* - string - optional
    *          - How far back from the current time to limit the returned feed files. The returned feed files will be those generated between the current time and the look-back time.<br /><br /><b>Example:</b> A value of <code>120</code> will limit the returned feed files to those generated in the past 2 hours (120 minutes). If 3 feed files have been generated in the past 2 hours, those 3 files will be returned. A feed file generated 4 hours earlier will not be returned.
    * @param array $headers
    *      - *X-EBAY-C-MARKETPLACE-ID* - string - required
    *          - This is the ID of the eBay marketplace on which to search for feed files.<br /><br /><b>Example:</b> <code>X-EBAY-C-MARKETPLACE-ID: EBAY_US</code>.<br /><br /><p>For a list of supported sites and other restrictions, see <a href="/api-docs/buy/feed/overview.html#API">API Restrictions</a>.
    * @return array
    *      - *fileMetadata* - array
    *          - An array of metadata values describing the available feed files that match the input criteria.
    *      - *href* - string
    *          - The URL to to the current set of results.
    *      - *limit* - integer
    *          - The number of results that will be displayed on each page, as set by the <b>limit</b> URI parameter.<br /><br /><b>Default:</b> 20
    *      - *next* - string
    *          - You can use this URL to retrieve the next page of results beyond those displayed on the page if there are more results that match the search criteria.
    *      - *total* - integer
    *          - The total number of matches for the search criteria.
    */
    public function getFiles(array $query, array $headers): array
    {
        return $this->api(array_merge(["/file"], $query), 'GET', [], $headers);
    }
    
}
