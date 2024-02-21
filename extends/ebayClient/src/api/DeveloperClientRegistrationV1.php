<?php

namespace lujie\ebay\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description <span class="tablenote"><b>Note:</b> The Client Registration API is not intended for use by developers who have previously registered for a Developer Account on the eBay platform.</span><br/>The Client Registration API provides Dynamic Client Registration for regulated Third Party Providers (TPPs) who are, or will be, engaged in financial transactions on behalf of individuals domiciled in the EU/UK. This is required by the EU's Second Payment Services Directive (PSD2) which requires all regulated Account Servicing Payment Service Providers (ASPSPs) to provide secure APIs to access account and payment services on behalf of account holders.<br/><br/>A successful registration response returns a <b>HTTP 201 Created</b> status code with a JSON payload [RFC7519] that includes registration information.
*/
class DeveloperClientRegistrationV1 extends \lujie\ebay\BaseEbayRestClient
{

    public $apiBaseUrl = 'https://tppz.ebay.com/developer/registration/v1';

                
    /**
     * @description <span class="tablenote"><b>Note:</b> The Client Registration API is not intended for use by developers who have previously registered for a Developer Account on the eBay platform.</span><br/>This call registers a new third party financial application with eBay.<br/><br/><div class="msgbox_important"><p class="msgbox_importantInDiv" data-mc-autonum="&lt;b&gt;&lt;span style=&quot;color: #dd1e31;&quot; class=&quot;mcFormatColor&quot;&gt;Important! &lt;/span&gt;&lt;/b&gt;"><span class="autonumber"><span><b><span style="color: #dd1e31;" class="mcFormatColor">Important!</span></b></span></span> When calling the <b>registerClient</b> method, Third Party Providers (TPPs) are required to pass their valid eIDAS certificate to eBay via Mutual Transport Layer Security (MTLS) handshake <i>Certificate Request</i> messages.</p></div><br/>A successful call returns an HTTP status code of <b>201 Created</b> and the response payload.<h4>Registering multiple applications</h4>A regulated third party provider (identified by a unique <i>organizationIdentifier</i>) may register up to 15 different applications with eBay provided the unique <a href="#request.software_id ">software_id</a> for each application is passed in at the time of registration.<br/><br/>Each <b>registerClient</b> call that passes in a unique <a href="#request.software_id ">software_id</a> will generate new <a href="#response.client_id ">client_id</a> and <a href="#response.client_secret ">client_secret</a> keypairs.<br/><br/>If a third party provider calls <b>registerClient</b> using a previously registered <a href="#request.software_id ">software_id</a>, the existing <a href="#response.client_id ">client_id</a> and <a href="#response.client_secret ">client_secret</a> keypairs are returned.<br/><br/><span class="tablenote"><b>Note:</b> For additional information about using an <i>organizationIdentifier</i>, refer to the following sections of <a href="https://www.etsi.org/deliver/etsi_ts/119400_119499/119495/01.05.01_60/ts_119495v010501p.pdf " target="_blank ">ETSI Technical Specification 119 495</a><ul><li>Section 5.2.1: Authorization Number or other recognized identifier for Open Banking;</li><li>Section 5.4: Profile Requirements for Digital Signatures.</li></ul></span>
     * @tag register
     * @param array $data This container stores information about the third party provider's financial application that is being registered.
     *      - *client_name* - string
     *          - User-friendly name for the third party financial application.<br/><br/><span class="tablenote"><b>Note:</b> Language tags are not supported. Therefore, <code>client_name</code> must be specified in English.</span>
     *      - *contacts* - array
     *          - This container stores an array of email addresses that can be used to contact the registrant.<br/><br/><span class="tablenote"><b>Note:</b> When more than one email address is provided, the first email in the array will be used as the developer account's email address. All other email addresses will be used as general contact information.</span>
     *      - *policy_uri* - string
     *          - The URL string pointing to a human-readable privacy policy document that describes how the third party provider collects, uses, retains, and discloses personal data.<br/><br/><span class="tablenote"><b>Note:</b> Only HTTPS URLs are supported for <code>policy_uri</code> strings.</span><br/><span class="tablenote"><b>Note:</b> This URL <b>must not</b> point to the eBay Privacy Policy.</span><br/>The value of this field <b>must</b> point to a valid and secure web page.<br/><br/><span class="tablenote"><b>Note:</b> Language tags are not supported. Therefore, <code>policy_uri</code> will be displayed in English.</span>
     *      - *redirect_uris* - array
     *          - An array of redirection URI strings for use in redirect-based flows such as the authorization code and implicit flows.<br/><br/><span class="tablenote"><b>Note:</b> Only the first URI string from the list will be used.</span><span class="tablenote"><b>Note:</b> Each redirection URI <b>must</b> be an absolute URI as defined by [RFC3986] Section 4.3.</span>
     *      - *software_id* - string
     *          - A unique identifier string assigned by the client developer or software publisher to identify the client software being registered.<br/><br/>Unlike <code>client_id</code> which should change between instances, the <CODE>software_id</code> should be the same value for all instances of the client software. That is, the <code>software_id</code> should remain unchanged across multiple updates or versions of the same piece of software. The value of this field is not intended to be human readable and is usually opaque to the client and authorization server.
     *      - *software_statement* - string
     *          - The Software Statement Assertion (SSA) that has been issued by the OpenBanking identifier.<br/><br/><span class="tablenote"><b>Note:</b> This value <i>must be</i> <b>Base64</b> encoded and not plain JSON.</span>Refer to <a href="https://datatracker.ietf.org/doc/html/rfc7591#section-2.3 " target= "_blank ">RFC 7591 - OAuth 2.0 Dynamic Client Registration Protocol</a> for complete information.
     * @param array $headers
     *      - *Content-Type* - string - required
     *          - This header indicates the format of the request body provided by the client. It's value should be set to <b>application/json</b>. <br><br> For more information, refer to <a href="/api-docs/static/rest-request-components.html#HTTP" target="_blank ">HTTP request headers</a>.
     */
    public function registerClient(array $data, array $headers = []): void
    {
        $this->api("/client/register", 'POST', $data, $headers);
    }
    
}