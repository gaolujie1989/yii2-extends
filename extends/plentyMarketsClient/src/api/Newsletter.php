<?php

namespace lujie\plentyMarkets\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The plentymarkets REST API expands the functionality of the plentymarkets CMS and allows access to resources, i.e. data records, via unique URI paths
*/
class Newsletter extends \lujie\plentyMarkets\BasePlentyMarketsRestClient
{

                
    /**
     * @description Deletes all entries.
     * @tag Newsletter
     * @return array
     */
    public function deleteNewsletter(): array
    {
        return $this->api("/rest/newsletters", 'DELETE');
    }
                
    /**
     * @description Lists all newsletter entries.
     * @tag Newsletter
     * @return array
     */
    public function getNewsletters(): array
    {
        return $this->api("/rest/newsletters");
    }
                
    /**
     * @description Creates a newsletter entry. The newsletter subject must be specified.
     * @tag Newsletter
     * @param array $data 
     * @param array $query
     *      - *subject* - string - required
     *          - The subject of the newsletter entry
     *      - *body* - string - optional
     *          - The body of the newsletter entry
     *      - *kind* - string - optional
     *          - The type of the entry. The content can be saved as plain text or in HTML format. Possible values: ['plain', 'html'].
     * @return array
     *      - *subject* - string
     *          - The subject of the newsletter entry
     *      - *body* - string
     *          - The body of the newsletter entry
     *      - *kind* - string
     *          - The type of the newsletter entry
     */
    public function createNewsletter(array $data, array $query): array
    {
        return $this->api(array_merge(["/rest/newsletters"], $query), 'POST', $data);
    }
                    
    /**
     * @description Sends a mail with a doubleOptIn. The ID of the costumer must be specified.
     * @tag Newsletter
     * @param int $contactId The ID of the costumer contact
     */
    public function createNewslettersDoubleOptInByContactId(int $contactId)
    {
        return $this->api("/rest/newsletters/double_opt_in/{$contactId}", 'POST');
    }
                    
    /**
     * @description Deletes all folders.
     * @tag Newsletter
     * @return array
     */
    public function deleteNewslettersFolder(): array
    {
        return $this->api("/rest/newsletters/folders", 'DELETE');
    }
                
    /**
     * @description Lists all newsletter folders.
     * @tag Newsletter
     * @param array $query
     *      - *folderId* - int - required
     *          - The ID of the newsletter folder
     * @return array
     */
    public function getNewslettersFolders(array $query): array
    {
        return $this->api(array_merge(["/rest/newsletters/folders"], $query));
    }
                
    /**
     * @description Creates a newsletter folder. The name of the folder must be specified.
     * @tag Newsletter
     * @param array $data 
     * @param array $query
     *      - *id* - int - optional
     *          - The ID of the newsletter folder
     *      - *name* - string - required
     *          - The name of the newsletter folder
     *      - *position* - int - optional
     *          - The position of the newsletter folder
     *      - *isDeletable* - boolean - optional
     *          - Flag that indicates if the newsletter folder can be deleted. Default value of is deletable is 1. The folders 'Customers' and 'Interested parties' are available by default and cannot be deleted.
     *      - *isSelectable* - boolean - optional
     *          - Flag that indicates if the newsletter folder can be selected by customers in the online store. If it is allowed, the folder will be displayed in the My account area of the online store. Customers will then be able to subscribe to the newsletters that are included in this folder.
     * @return array
     *      - *id* - integer
     *          - The ID of the newsletter folder
     *      - *name* - string
     *          - The name of the newsletter folder
     *      - *isDeletable* - boolean
     *          - Flag that indicates if the newsletter folder can be deleted. The folders 'Customers' and 'Interested parties' are available by default and cannot be deleted.
     *      - *position* - integer
     *          - The position of the newsletter folder
     *      - *isSelectable* - boolean
     *          - Flag that indicates if the newsletter folder can be selected by customers in the online store. If this is allowed, the folder will be displayed in the My account area of the online store. Customers will then be able to subscribe to the newsletters that are included in this folder.
     */
    public function createNewslettersFolder(array $data, array $query): array
    {
        return $this->api(array_merge(["/rest/newsletters/folders"], $query), 'POST', $data);
    }
                    
    /**
     * @description Deletes a folder. The ID of the folder must be specified.
     * @tag Newsletter
     * @param int $folderId The ID of the newsletter folder
     * @return array
     *      - *id* - integer
     *          - The ID of the newsletter folder
     *      - *name* - string
     *          - The name of the newsletter folder
     *      - *isDeletable* - boolean
     *          - Flag that indicates if the newsletter folder can be deleted. The folders 'Customers' and 'Interested parties' are available by default and cannot be deleted.
     *      - *position* - integer
     *          - The position of the newsletter folder
     *      - *isSelectable* - boolean
     *          - Flag that indicates if the newsletter folder can be selected by customers in the online store. If this is allowed, the folder will be displayed in the My account area of the online store. Customers will then be able to subscribe to the newsletters that are included in this folder.
     */
    public function deleteNewslettersFolderByFolderId(int $folderId): array
    {
        return $this->api("/rest/newsletters/folders/{$folderId}", 'DELETE');
    }
                
    /**
     * @description Lists details of a folder. The ID of the folder must be specified.
     * @tag Newsletter
     * @param int $folderId The ID of the newsletter folder.
     * @return array
     *      - *id* - integer
     *          - The ID of the newsletter folder
     *      - *name* - string
     *          - The name of the newsletter folder
     *      - *isDeletable* - boolean
     *          - Flag that indicates if the newsletter folder can be deleted. The folders 'Customers' and 'Interested parties' are available by default and cannot be deleted.
     *      - *position* - integer
     *          - The position of the newsletter folder
     *      - *isSelectable* - boolean
     *          - Flag that indicates if the newsletter folder can be selected by customers in the online store. If this is allowed, the folder will be displayed in the My account area of the online store. Customers will then be able to subscribe to the newsletters that are included in this folder.
     */
    public function getNewslettersFolderByFolderId(int $folderId): array
    {
        return $this->api("/rest/newsletters/folders/{$folderId}");
    }
                
    /**
     * @description Updates a folder. The ID of the folder must be specified.
     * @tag Newsletter
     * @param int $folderId The ID of the newsletter folder
     * @param array $query
     *      - *name* - string - optional
     *          - The name of the newsletter folder
     *      - *position* - int - optional
     *          - The position of the newsletter folder
     *      - *clientIds* - array - optional
     *          - The IDs of the clients (stores). It is possible to determine which clients (stores) the newsletter folder is visible for. 
     *      - *isDeletable* - boolean - optional
     *          - Flag that indicates if the newsletter folder can be deleted. The folders 'Customers' and 'Interested parties' are available by default and cannot be deleted.
     *      - *isSelectable* - boolean - optional
     *          - Flag that indicates if the newsletter folder can be selected by customers in the online store. If it is allowed, the folder will be displayed in the My account area of the online store. Customers will then be able to subscribe to the newsletters that are included in this folder.
     * @return array
     *      - *id* - integer
     *          - The ID of the newsletter folder
     *      - *name* - string
     *          - The name of the newsletter folder
     *      - *isDeletable* - boolean
     *          - Flag that indicates if the newsletter folder can be deleted. The folders 'Customers' and 'Interested parties' are available by default and cannot be deleted.
     *      - *position* - integer
     *          - The position of the newsletter folder
     *      - *isSelectable* - boolean
     *          - Flag that indicates if the newsletter folder can be selected by customers in the online store. If this is allowed, the folder will be displayed in the My account area of the online store. Customers will then be able to subscribe to the newsletters that are included in this folder.
     */
    public function updateNewslettersFolderByFolderId(int $folderId, array $query = []): array
    {
        return $this->api(array_merge(["/rest/newsletters/folders/{$folderId}"], $query), 'PUT');
    }
                    
    /**
     * @description Lists all recipients of a folder. The ID of the folder must be specified.
     * @tag Newsletter
     * @param int $folderId The ID of the newsletter folder.
     * @return array
     */
    public function getNewslettersFoldersRecipientsByFolderId(int $folderId): array
    {
        return $this->api("/rest/newsletters/folders/{$folderId}/recipients");
    }
                
    /**
     * @description Lists recipients from all newsletter folders.
     * @tag Newsletter
     * @param array $query
     *      - *columns* - array - optional
     *          - Filter that restricts the search result to specific columns
     *      - *page* - int - optional
     *          - The page to get. The default page that will be returned is page 1.
     *      - *itemsPerPage* - int - optional
     *          - The number of orders to be displayed per page. The default number of orders per page is 50.
     *      - *folderId* - int - optional
     *          - Filter that restricts the search result to a specific folderId.
     *      - *isConfirmed* - boolean - optional
     *          - Filter that restricts the search result to confirmed recipients.
     *      - *with* - array - optional
     *          - Load additional relations for a Recipient. CURRENTLY NOT AVAILABLE
     *      - *recipientId* - int - required
     *          - The ID of the recipient
     * @return Iterator
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
     *      - *entries* - 
     */
    public function eachNewslettersListRecipients(array $query): Iterator
    {
        return $this->eachInternal('getNewslettersListRecipients', func_get_args());
    }
        
    /**
     * @description Lists recipients from all newsletter folders.
     * @tag Newsletter
     * @param array $query
     *      - *columns* - array - optional
     *          - Filter that restricts the search result to specific columns
     *      - *page* - int - optional
     *          - The page to get. The default page that will be returned is page 1.
     *      - *itemsPerPage* - int - optional
     *          - The number of orders to be displayed per page. The default number of orders per page is 50.
     *      - *folderId* - int - optional
     *          - Filter that restricts the search result to a specific folderId.
     *      - *isConfirmed* - boolean - optional
     *          - Filter that restricts the search result to confirmed recipients.
     *      - *with* - array - optional
     *          - Load additional relations for a Recipient. CURRENTLY NOT AVAILABLE
     *      - *recipientId* - int - required
     *          - The ID of the recipient
     * @return Iterator
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
     *      - *entries* - 
     */
    public function batchNewslettersListRecipients(array $query): Iterator
    {
        return $this->batchInternal('getNewslettersListRecipients', func_get_args());
    }
    
    /**
     * @description Lists recipients from all newsletter folders.
     * @tag Newsletter
     * @param array $query
     *      - *columns* - array - optional
     *          - Filter that restricts the search result to specific columns
     *      - *page* - int - optional
     *          - The page to get. The default page that will be returned is page 1.
     *      - *itemsPerPage* - int - optional
     *          - The number of orders to be displayed per page. The default number of orders per page is 50.
     *      - *folderId* - int - optional
     *          - Filter that restricts the search result to a specific folderId.
     *      - *isConfirmed* - boolean - optional
     *          - Filter that restricts the search result to confirmed recipients.
     *      - *with* - array - optional
     *          - Load additional relations for a Recipient. CURRENTLY NOT AVAILABLE
     *      - *recipientId* - int - required
     *          - The ID of the recipient
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
     *      - *entries* - 
     */
    public function getNewslettersListRecipients(array $query): array
    {
        return $this->api(array_merge(["/rest/newsletters/list_recipients"], $query));
    }
                    
    /**
     * @description Deletes a recipients.
     * @tag Newsletter
     * @return array
     */
    public function deleteNewslettersRecipient(): array
    {
        return $this->api("/rest/newsletters/recipients", 'DELETE');
    }
                
    /**
     * @description Lists all recipients of a folder.
     * @tag Newsletter
     * @param array $query
     *      - *email* - string - optional
     *          - Filter that restricts the search result to the email address of the recipient
     *      - *folderId* - int - optional
     *          - Filter that restricts the search result to the folder ID
     *      - *recipientId* - int - optional
     *          - Filter that restricts the search result to the recipient ID
     * @return array
     */
    public function getNewslettersRecipients(array $query = []): array
    {
        return $this->api(array_merge(["/rest/newsletters/recipients"], $query));
    }
                
    /**
     * @description Creates a recipient.
     * @tag Newsletter
     * @param array $data 
     * @param array $query
     *      - *email* - string - optional
     *          - The email address of the recipient
     *      - *firstName* - string - optional
     *          - The first name of the recipient
     *      - *lastName* - string - optional
     *          - The last name of the recipient
     *      - *folderIds* - array - optional
     *          - The IDs of the newsletter folders. These folders were selected by the customer in the online store in order to receive newsletters included in these folders.
     *      - *isFrontend* - boolean - optional
     *          - Value that indicates if the REST call was retrieved from the front end. Possible values are: 'true' or 'false'. True = The REST call was retrieved from the front end. False = The REST call was not retrieved from the front end.
     *      - *ignoreVisibility* - boolean - optional
     *          - Value that indicates if the REST call considers folders without visibility. Possible value: 'true'. If the value 'true' is set, the folder visibility will be ignored. This means that both visible and invisible folders will be listed depending on the folder IDs entered in the REST call.
     *      - *ipAddress* - string - optional
     *          - The IP address from where the customer has confirmed the newsletter
     * @return array
     */
    public function createNewslettersRecipient(array $data, array $query = []): array
    {
        return $this->api(array_merge(["/rest/newsletters/recipients"], $query), 'POST', $data);
    }
                    
    /**
     * @description Deletes a recipient. The ID of the recipient must be specified.
     * @tag Newsletter
     * @param int $recipientId The ID of the recipient
     * @return array
     *      - *id* - integer
     *          - The ID of the newsletter recipient
     *      - *folderId* - integer
     *          - The ID of the newsletter folder
     *      - *contactId* - integer
     *          - The ID of the contact
     *      - *firstName* - string
     *          - The first name of the recipient
     *      - *lastName* - string
     *          - The last name of the recipient
     *      - *email* - string
     *          - The email address of the recipient
     *      - *gender* - string
     *          - The gender of the recipient
     *      - *birthday* - string
     *          - The birthday of the recipient
     *      - *timestamp* - integer
     *          - The timestamp when the newsletter email was sent to the recipient
     *      - *templateLang* - string
     *          - The language of the newsletter email template
     *      - *confirmedTimestamp* - integer
     *          - The timestamp when the recipient confirmed the newsletter subscription
     *      - *confirmAuthString* - string
     *          - The key that is automatically generated by the system. This key recognises the user regardless whether the user is logged in to the system and will then set the confirmation timestamp.
     *      - *confirmationURL* - string
     *          - The url with which the customer has confirmed the newsletter
     */
    public function deleteNewslettersRecipientByRecipientId(int $recipientId): array
    {
        return $this->api("/rest/newsletters/recipients/{$recipientId}", 'DELETE');
    }
                
    /**
     * @description Lists a recipient. The ID of the recipient must be specified.
     * @tag Newsletter
     * @param int $recipientId The ID of the newsletter folder.
     * @return array
     *      - *id* - integer
     *          - The ID of the newsletter recipient
     *      - *folderId* - integer
     *          - The ID of the newsletter folder
     *      - *contactId* - integer
     *          - The ID of the contact
     *      - *firstName* - string
     *          - The first name of the recipient
     *      - *lastName* - string
     *          - The last name of the recipient
     *      - *email* - string
     *          - The email address of the recipient
     *      - *gender* - string
     *          - The gender of the recipient
     *      - *birthday* - string
     *          - The birthday of the recipient
     *      - *timestamp* - integer
     *          - The timestamp when the newsletter email was sent to the recipient
     *      - *templateLang* - string
     *          - The language of the newsletter email template
     *      - *confirmedTimestamp* - integer
     *          - The timestamp when the recipient confirmed the newsletter subscription
     *      - *confirmAuthString* - string
     *          - The key that is automatically generated by the system. This key recognises the user regardless whether the user is logged in to the system and will then set the confirmation timestamp.
     *      - *confirmationURL* - string
     *          - The url with which the customer has confirmed the newsletter
     */
    public function getNewslettersRecipientByRecipientId(int $recipientId): array
    {
        return $this->api("/rest/newsletters/recipients/{$recipientId}");
    }
                
    /**
     * @description Updates a recipient that is assigned to a folder. The ID of the recipient must be specified.
     * @tag Newsletter
     * @param int $recipientId The ID of the recipient
     * @param array $data 
     * @param array $query
     *      - *email* - string - optional
     *          - The email address of the newsletter recipient
     *      - *firstName* - string - optional
     *          - The first name of the newsletter recipient
     *      - *lastName* - string - optional
     *          - The last name of the newsletter recipient
     *      - *folderIds* - array - optional
     *          - DEPRECATED: The IDs of the newsletter folders. These folders were selected by the customer in the online store in order to receive newsletters included in these folders.
     *      - *folderId* - int - optional
     *          - The ID of the newsletter folder.
     *      - *ipAddress* - string - optional
     *          - The IP address from where the customer has confirmed the newsletter
     *      - *birthday* - string - optional
     *          - The customer birthday as Date string (e.g. '1982-11-24', '1982/11/24' or '24.11.1982')
     *      - *gender* - string - optional
     *          - The gender of the customer, one of the following values: 'm','f','d'.
     * @return array
     *      - *id* - integer
     *          - The ID of the newsletter recipient
     *      - *folderId* - integer
     *          - The ID of the newsletter folder
     *      - *contactId* - integer
     *          - The ID of the contact
     *      - *firstName* - string
     *          - The first name of the recipient
     *      - *lastName* - string
     *          - The last name of the recipient
     *      - *email* - string
     *          - The email address of the recipient
     *      - *gender* - string
     *          - The gender of the recipient
     *      - *birthday* - string
     *          - The birthday of the recipient
     *      - *timestamp* - integer
     *          - The timestamp when the newsletter email was sent to the recipient
     *      - *templateLang* - string
     *          - The language of the newsletter email template
     *      - *confirmedTimestamp* - integer
     *          - The timestamp when the recipient confirmed the newsletter subscription
     *      - *confirmAuthString* - string
     *          - The key that is automatically generated by the system. This key recognises the user regardless whether the user is logged in to the system and will then set the confirmation timestamp.
     *      - *confirmationURL* - string
     *          - The url with which the customer has confirmed the newsletter
     */
    public function updateNewslettersRecipientByRecipientId(int $recipientId, array $data, array $query = []): array
    {
        return $this->api(array_merge(["/rest/newsletters/recipients/{$recipientId}"], $query), 'PUT', $data);
    }
                    
    /**
     * @description Deletes an entry. The ID of the entry must be specified.
     * @tag Newsletter
     * @param int $entryId The ID of the newsletter entry
     * @return array
     *      - *subject* - string
     *          - The subject of the newsletter entry
     *      - *body* - string
     *          - The body of the newsletter entry
     *      - *kind* - string
     *          - The type of the newsletter entry
     */
    public function deleteNewsletterByEntryId(int $entryId): array
    {
        return $this->api("/rest/newsletters/{$entryId}", 'DELETE');
    }
                
    /**
     * @description Lists details of an entry. The ID of the entry must be specified.
     * @tag Newsletter
     * @param int $entryId The ID of the newsletter entry.
     * @return array
     *      - *subject* - string
     *          - The subject of the newsletter entry
     *      - *body* - string
     *          - The body of the newsletter entry
     *      - *kind* - string
     *          - The type of the newsletter entry
     */
    public function getNewsletterByEntryId(int $entryId): array
    {
        return $this->api("/rest/newsletters/{$entryId}");
    }
                
    /**
     * @description Updates an entry. The ID of the entry must be specified.
     * @tag Newsletter
     * @param int $entryId The ID of the entry
     * @param array $query
     *      - *subject* - string - optional
     *          - The subject of the entry
     *      - *body* - string - optional
     *          - The body of the entry
     *      - *kind* - string - optional
     *          - The type of the entry. The content can be saved as plain text or in HTML format. Possible values: ['plain', 'html'].
     *      - *folderId* - int - required
     *          - The ID of the newsletter folder
     * @return array
     *      - *subject* - string
     *          - The subject of the newsletter entry
     *      - *body* - string
     *          - The body of the newsletter entry
     *      - *kind* - string
     *          - The type of the newsletter entry
     */
    public function updateNewsletterByEntryId(int $entryId, array $query): array
    {
        return $this->api(array_merge(["/rest/newsletters/{$entryId}"], $query), 'PUT');
    }
    
}
