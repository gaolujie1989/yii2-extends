<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description With the A+ Content API, you can build applications that help selling partners add rich marketing content to their Amazon product detail pages. A+ content helps selling partners share their brand and product story, which helps buyers make informed purchasing decisions. Selling partners assemble content by choosing from content modules and adding images and text.
*/
class AplusContent20201101 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Returns a list of all A+ Content documents assigned to a selling partner. This operation returns only the metadata of the A+ Content documents. Call the getContentDocument operation to get the actual contents of the A+ Content documents.

**Usage Plans:**

| Plan type | Rate (requests per second) | Burst |
| ---- | ---- | ---- |
|Default| 10 | 10 |
|Selling partner specific| Variable | Variable |

The x-amzn-RateLimit-Limit response header returns the usage plan rate limits that were applied to the requested operation. Rate limits for some selling partners will vary from the default rate and burst shown in the table above. For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag aplusContent
     * @param array $query
     *      - *marketplaceId* - string - required
     *          - The identifier for the marketplace where the A+ Content is published.
     *      - *pageToken* - string - optional
     *          - A page token from the nextPageToken response element returned by your previous call to this operation. nextPageToken is returned when the results of a call exceed the page size. To get the next page of results, call the operation and include pageToken as the only parameter. Specifying pageToken with any other parameter will cause the request to fail. When no nextPageToken value is returned there are no more pages to return. A pageToken value is not usable across different operations.
     * @return array
     */
    public function searchContentDocuments(array $query): array
    {
        return $this->api(array_merge(["/aplus/2020-11-01/contentDocuments"], $query));
    }
                
    /**
     * @description Creates a new A+ Content document.

**Usage Plans:**

| Plan type | Rate (requests per second) | Burst |
| ---- | ---- | ---- |
|Default| 10 | 10 |
|Selling partner specific| Variable | Variable |

The x-amzn-RateLimit-Limit response header returns the usage plan rate limits that were applied to the requested operation. Rate limits for some selling partners will vary from the default rate and burst shown in the table above. For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag aplusContent
     * @param array $query
     *      - *marketplaceId* - string - required
     *          - The identifier for the marketplace where the A+ Content is published.
     * @param array $data 
     * @return array
     */
    public function createContentDocument(array $query, array $data): array
    {
        return $this->api(array_merge(["/aplus/2020-11-01/contentDocuments"], $query), 'POST', $data);
    }
                        
    /**
     * @description Returns an A+ Content document, if available.

**Usage Plans:**

| Plan type | Rate (requests per second) | Burst |
| ---- | ---- | ---- |
|Default| 10 | 10 |
|Selling partner specific| Variable | Variable |

The x-amzn-RateLimit-Limit response header returns the usage plan rate limits that were applied to the requested operation. Rate limits for some selling partners will vary from the default rate and burst shown in the table above. For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag aplusContent
     * @param string $contentReferenceKey The unique reference key for the A+ Content document. A content reference key cannot form a permalink and may change in the future. A content reference key is not guaranteed to match any A+ Content identifier.
     * @param array $query
     *      - *marketplaceId* - string - required
     *          - The identifier for the marketplace where the A+ Content is published.
     *      - *includedDataSet* - array - required
     *          - The set of A+ Content data types to include in the response.
     * @return array
     */
    public function getContentDocument(string $contentReferenceKey, array $query): array
    {
        return $this->api(array_merge(["/aplus/2020-11-01/contentDocuments/{$contentReferenceKey}"], $query));
    }
                
    /**
     * @description Updates an existing A+ Content document.

**Usage Plans:**

| Plan type | Rate (requests per second) | Burst |
| ---- | ---- | ---- |
|Default| 10 | 10 |
|Selling partner specific| Variable | Variable |

The x-amzn-RateLimit-Limit response header returns the usage plan rate limits that were applied to the requested operation. Rate limits for some selling partners will vary from the default rate and burst shown in the table above. For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag aplusContent
     * @param string $contentReferenceKey The unique reference key for the A+ Content document. A content reference key cannot form a permalink and may change in the future. A content reference key is not guaranteed to match any A+ Content identifier.
     * @param array $query
     *      - *marketplaceId* - string - required
     *          - The identifier for the marketplace where the A+ Content is published.
     * @param array $data 
     * @return array
     */
    public function updateContentDocument(string $contentReferenceKey, array $query, array $data): array
    {
        return $this->api(array_merge(["/aplus/2020-11-01/contentDocuments/{$contentReferenceKey}"], $query), 'POST', $data);
    }
                        
    /**
     * @description Returns a list of ASINs related to the specified A+ Content document, if available. If you do not include the asinSet parameter, the operation returns all ASINs related to the content document.

**Usage Plans:**

| Plan type | Rate (requests per second) | Burst |
| ---- | ---- | ---- |
|Default| 10 | 10 |
|Selling partner specific| Variable | Variable |

The x-amzn-RateLimit-Limit response header returns the usage plan rate limits that were applied to the requested operation. Rate limits for some selling partners will vary from the default rate and burst shown in the table above. For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag aplusContent
     * @param string $contentReferenceKey The unique reference key for the A+ Content document. A content reference key cannot form a permalink and may change in the future. A content reference key is not guaranteed to match any A+ Content identifier.
     * @param array $query
     *      - *marketplaceId* - string - required
     *          - The identifier for the marketplace where the A+ Content is published.
     *      - *includedDataSet* - array - optional
     *          - The set of A+ Content data types to include in the response. If you do not include this parameter, the operation returns the related ASINs without metadata.
     *      - *asinSet* - array - optional
     *          - The set of ASINs.
     *      - *pageToken* - string - optional
     *          - A page token from the nextPageToken response element returned by your previous call to this operation. nextPageToken is returned when the results of a call exceed the page size. To get the next page of results, call the operation and include pageToken as the only parameter. Specifying pageToken with any other parameter will cause the request to fail. When no nextPageToken value is returned there are no more pages to return. A pageToken value is not usable across different operations.
     * @return array
     */
    public function listContentDocumentAsinRelations(string $contentReferenceKey, array $query): array
    {
        return $this->api(array_merge(["/aplus/2020-11-01/contentDocuments/{$contentReferenceKey}/asins"], $query));
    }
                
    /**
     * @description Replaces all ASINs related to the specified A+ Content document, if available. This may add or remove ASINs, depending on the current set of related ASINs. Removing an ASIN has the side effect of suspending the content document from that ASIN.

**Usage Plans:**

| Plan type | Rate (requests per second) | Burst |
| ---- | ---- | ---- |
|Default| 10 | 10 |
|Selling partner specific| Variable | Variable |

The x-amzn-RateLimit-Limit response header returns the usage plan rate limits that were applied to the requested operation. Rate limits for some selling partners will vary from the default rate and burst shown in the table above. For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag aplusContent
     * @param string $contentReferenceKey The unique reference key for the A+ Content document. A content reference key cannot form a permalink and may change in the future. A content reference key is not guaranteed to match any A+ content identifier.
     * @param array $query
     *      - *marketplaceId* - string - required
     *          - The identifier for the marketplace where the A+ Content is published.
     * @param array $data 
     * @return array
     */
    public function postContentDocumentAsinRelations(string $contentReferenceKey, array $query, array $data): array
    {
        return $this->api(array_merge(["/aplus/2020-11-01/contentDocuments/{$contentReferenceKey}/asins"], $query), 'POST', $data);
    }
                        
    /**
     * @description Checks if the A+ Content document is valid for use on a set of ASINs.

**Usage Plans:**

| Plan type | Rate (requests per second) | Burst |
| ---- | ---- | ---- |
|Default| 10 | 10 |
|Selling partner specific| Variable | Variable |

The x-amzn-RateLimit-Limit response header returns the usage plan rate limits that were applied to the requested operation. Rate limits for some selling partners will vary from the default rate and burst shown in the table above. For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag aplusContent
     * @param array $query
     *      - *marketplaceId* - string - required
     *          - The identifier for the marketplace where the A+ Content is published.
     *      - *asinSet* - array - optional
     *          - The set of ASINs.
     * @param array $data 
     * @return array
     */
    public function validateContentDocumentAsinRelations(array $query, array $data): array
    {
        return $this->api(array_merge(["/aplus/2020-11-01/contentAsinValidations"], $query), 'POST', $data);
    }
                        
    /**
     * @description Searches for A+ Content publishing records, if available.

**Usage Plans:**

| Plan type | Rate (requests per second) | Burst |
| ---- | ---- | ---- |
|Default| 10 | 10 |
|Selling partner specific| Variable | Variable |

The x-amzn-RateLimit-Limit response header returns the usage plan rate limits that were applied to the requested operation. Rate limits for some selling partners will vary from the default rate and burst shown in the table above. For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag aplusContent
     * @param array $query
     *      - *marketplaceId* - string - required
     *          - The identifier for the marketplace where the A+ Content is published.
     *      - *asin* - string - required
     *          - The Amazon Standard Identification Number (ASIN).
     *      - *pageToken* - string - optional
     *          - A page token from the nextPageToken response element returned by your previous call to this operation. nextPageToken is returned when the results of a call exceed the page size. To get the next page of results, call the operation and include pageToken as the only parameter. Specifying pageToken with any other parameter will cause the request to fail. When no nextPageToken value is returned there are no more pages to return. A pageToken value is not usable across different operations.
     * @return array
     */
    public function searchContentPublishRecords(array $query): array
    {
        return $this->api(array_merge(["/aplus/2020-11-01/contentPublishRecords"], $query));
    }
                        
    /**
     * @description Submits an A+ Content document for review, approval, and publishing.

**Usage Plans:**

| Plan type | Rate (requests per second) | Burst |
| ---- | ---- | ---- |
|Default| 10 | 10 |
|Selling partner specific| Variable | Variable |

The x-amzn-RateLimit-Limit response header returns the usage plan rate limits that were applied to the requested operation. Rate limits for some selling partners will vary from the default rate and burst shown in the table above. For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag aplusContent
     * @param string $contentReferenceKey The unique reference key for the A+ Content document. A content reference key cannot form a permalink and may change in the future. A content reference key is not guaranteed to match any A+ content identifier.
     * @param array $query
     *      - *marketplaceId* - string - required
     *          - The identifier for the marketplace where the A+ Content is published.
     * @return array
     */
    public function postContentDocumentApprovalSubmission(string $contentReferenceKey, array $query): array
    {
        return $this->api(array_merge(["/aplus/2020-11-01/contentDocuments/{$contentReferenceKey}/approvalSubmissions"], $query), 'POST');
    }
                        
    /**
     * @description Submits a request to suspend visible A+ Content. This neither deletes the content document nor the ASIN relations.

**Usage Plans:**

| Plan type | Rate (requests per second) | Burst |
| ---- | ---- | ---- |
|Default| 10 | 10 |
|Selling partner specific| Variable | Variable |

The x-amzn-RateLimit-Limit response header returns the usage plan rate limits that were applied to the requested operation. Rate limits for some selling partners will vary from the default rate and burst shown in the table above. For more information, see "Usage Plans and Rate Limits" in the Selling Partner API documentation.
     * @tag aplusContent
     * @param string $contentReferenceKey The unique reference key for the A+ Content document. A content reference key cannot form a permalink and may change in the future. A content reference key is not guaranteed to match any A+ content identifier.
     * @param array $query
     *      - *marketplaceId* - string - required
     *          - The identifier for the marketplace where the A+ Content is published.
     * @return array
     */
    public function postContentDocumentSuspendSubmission(string $contentReferenceKey, array $query): array
    {
        return $this->api(array_merge(["/aplus/2020-11-01/contentDocuments/{$contentReferenceKey}/suspendSubmissions"], $query), 'POST');
    }
        
}
