<?php

namespace lujie\ebay\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Media API allows sellers to create, upload, and fetch videos.
*/
class CommerceMediaV1Beta extends \lujie\ebay\BaseEbayRestClient
{

    public $apiBaseUrl = 'https://apim.ebay.com/commerce/media/v1_beta';

                
    /**
     * @description This method creates a video. When using this method, specify the <b>title</b>, <b>size</b>, and <b>classification</b> of the video to be created. <b>Description</b> is an optional field for this method.<br /><br /><span class="tablenote"><span style="color:#478415"><strong>Tip:</strong></span> See <a href="https://www.ebay.com/help/selling/listings/creating-managing-listings/add-video-to-listing?id=5272#section3" target="_blank">Adding a video to your listing</a> in the eBay Seller Center for details about video formatting requirements and restrictions, or visit the relevant eBay site help pages for the region in which the listings will be posted.</span><br /><br />When a video is successfully created, the method returns the HTTP Status Code <code>201 Created.</code>The method also returns the location response header containing the <b>video ID</b>, which you can use to retrieve the video.<br /><br /><span class="tablenote"><span style="color:#004680"><strong>Note:</strong></span> There is no ability to edit metadata on videos at this time. There is also no method to delete videos.</span><br /><br />To upload a created video, use the <a href=" /api-docs/commerce/media/resources/video/methods/uploadVideo" target="_blank">uploadVideo</a> method.
     * @tag video
     * @param array $data 
     *      - *classification* - array
     *          - The intended use for this video content. The video’s classification is used to associate the video with a user or seller. Currently, the classification of all videos should be set to <code>ITEM</code>.
     *      - *description* - string
     *          - The description of the video.
     *      - *size* - integer
     *          - The size, in bytes, of the video content.
     *      - *title* - string
     *          - The title of the video.
     * @param array $headers
     *      - *Content-Type* - string - required
     *          - This header indicates the format of the request body provided by the client. It's value should be set to <b>application/json</b>. <br><br> For more information, refer to <a href="/api-docs/static/rest-request-components.html#HTTP" target="_blank ">HTTP request headers</a>.
     */
    public function createVideo(array $data, array $headers): void
    {
        $this->api("/video", 'POST', $data, $headers);
    }
                    
    /**
     * @description This method retrieves a video's metadata and content given a specified <b>video ID</b>. The method returns the <b>title</b>, <b>size</b>, <b>classification</b>, <b>description</b>, <b>video ID</b>, <b>playList</b>, <b>status</b>, <b>status message</b> (if any), <b>expiration  date</b>, and <b>thumbnail</b> image of the retrieved video. <p>The video’s <b>title</b>, <b>size</b>, <b>classification</b>, and <b>description</b> are set using the <a href=" /api-docs/commerce/media/resources/video/methods/createVideo" target="_blank">createVideo</a> method.</p> <p>The video's <b>playList</b> contains two URLs that link to instances of the streaming video based on the supported protocol.</p><p>The <b>status</b> field contains the current status of the video. After a video upload is successfully completed, the video's <b>status</b> will show as <code>PROCESSING</code> until the video reaches one of the terminal states of <code>LIVE</code>, <code>BLOCKED</code> or <code>PROCESSING_FAILED</code>.<p> If a video's processing fails, it could be because the file is corrupted, is too large, or its size doesn’t match what was provided in the metadata. Refer to the error messages to determine the cause of the video’s failure to upload.</p> <p> The <b>status message</b> will indicate why a video was blocked from uploading.</p><p>The video’s <b>expiration date</b> is automatically set to 365 days (one year) after the video’s initial creation.<p>The video's <b>thumbnail</b> image is automatically generated when the video is created.
     * @tag video
     * @param string $videoId The <b>video ID</b> for the video to be retrieved.
     * @return array
     *      - *classification* - array
     *          - The intended use for this video content. The video’s classification is used to associate the video with a user or seller. Currently, the classification of all videos should be set to <code>ITEM</code>.
     *      - *description* - string
     *          - The description of the video. The video description is an optional field that can be set using the <a href=" /api-docs/commerce/media/resources/video/methods/createVideo" target="_blank">createVideo</a> method.
     *      - *expirationDate* - string
     *          - The expiration date of the video in Coordinated Universal Time (UTC). The video’s expiration date is automatically set to 365 days (one year) after the video’s initial upload.
     *      - *moderation* - 
     *          - The video moderation information that is returned if a video is blocked by moderators.<br /><br /><span class="tablenote"><span style="color:#478415"><strong>Tip:</strong></span> See <a href="https://www.ebay.com/help/selling/listings/creating-managing-listings/add-video-to-listing?id=5272#section2" target="_blank">Video moderation and restrictions</a> in the eBay Seller Center for details about video moderation.</span><br /><br />If the video status is <code>BLOCKED</code>, ensure that the video complies with eBay's video formatting and content guidelines. Afterwards, begin the video creation and upload procedure anew using the <strong>createVideo</strong> and <strong>uploadVideo</strong> methods.
     *      - *playLists* - array
     *          - The playlist created for the uploaded video, which provides the streaming video URLs to play the video. The supported streaming video protocols are DASH (Dynamic Adaptive Streaming over HTTP) and HLS (HTTP Live Streaming). The playlist will only be generated if a video is successfully uploaded with a status of <code>LIVE</code>.
     *      - *size* - integer
     *          - The size, in bytes, of the video content.
     *      - *status* - string
     *          - The status of the current video resource. For implementation help, refer to <a href='https://developer.ebay.com/api-docs/commerce/media/types/api:VideoStatusEnum'>eBay API documentation</a>
     *      - *statusMessage* - string
     *          - The <b>statusMessage</b> field contains additional information on the status. For example, information on why processing might have failed or if the video was blocked.
     *      - *thumbnail* - 
     *          - The URL of the thumbnail image of the video. The thumbnail image's URL must be an eBayPictureURL (EPS URL).
     *      - *title* - string
     *          - The title of the video.
     *      - *videoId* - string
     *          - The unique ID of the video.
     */
    public function getVideo(string $videoId): array
    {
        return $this->api("/video/{$videoId}");
    }
                    
    /**
     * @description This method associates the specified file with the specified <b>video ID</b> and uploads the input file. After the file has been uploaded the processing of the file begins.<br /><br /><span class="tablenote"><span style="color:#004680"><strong>Note:</strong></span> The size of the video to be uploaded must exactly match the size of the video's input stream that was set in the <a href=" /api-docs/commerce/media/resources/video/methods/createVideo" target="_blank">createVideo</a> method. If the sizes do not match, the video will not upload successfully.</span><br /><br />When a video is successfully uploaded, it returns the HTTP Status Code <code>200 OK</code>.<br /><br />The status flow is <code>PENDING_UPLOAD</code> > <code>PROCESSING</code> > <code>LIVE</code>,  <code>PROCESSING_FAILED</code>, or <code>BLOCKED</code>. After a video upload is successfully completed, the status will show as <code>PROCESSING</code> until the video reaches one of the terminal states of <code>LIVE</code>, <code>BLOCKED</code>, or <code>PROCESSING_FAILED</code>. If the size information (in bytes) provided is incorrect, the API will throw an error.<br /><br /><span class="tablenote"><span style="color:#478415"><strong>Tip:</strong></span> See <a href="https://www.ebay.com/help/selling/listings/creating-managing-listings/add-video-to-listing?id=5272#section3" target="_blank">Adding a video to your listing</a> in the eBay Seller Center for details about video formatting requirements and restrictions, or visit the relevant eBay site help pages for the region in which the listings will be posted.</span><br /><br />To retrieve an uploaded video, use the <a href="/api-docs/commerce/media/resources/video/methods/getVideo" target="_blank">getVideo</a> method.
     * @tag video
     * @param string $videoId The <b>video ID</b> for the uploaded video.
     * @param string $data The request payload for this method is the input stream for the video source. The input source must be an .mp4 file of the type MPEG-4 Part 10 or Advanced Video Coding (MPEG-4 AVC).
     * @param array $headers
     *      - *Content-Length* - string - optional
     *          - Use this header to specify the content length for the upload. Use Content-Range: bytes {1}-{2}/{3} and Content-Length:{4} headers.<br /><br /><span class="tablenote"><span style="color:#004680"><strong>Note:</strong></span> This header is optional and is only required for <i>resumable</i> uploads (when an upload is interrupted and must be resumed from a certain point).</span>
     *      - *Content-Range* - string - optional
     *          - Use this header to specify the content range for the upload. The Content-Range should be of the following bytes ((?:[0-9]+-[0-9]+)|\\\\*)/([0-9]+|\\\\*) pattern.<br /><br /><span class="tablenote"><span style="color:#004680"><strong>Note:</strong></span> This header is optional and is only required for <i>resumable</i> uploads (when an upload is interrupted and must be resumed from a certain point).</span>
     *      - *Content-Type* - string - required
     *          - Use this header to specify the content type for the upload. The Content-Type should be set to <code>application/octet-stream</code>.
     */
    public function uploadVideo(string $videoId, string $data, array $headers): void
    {
        $this->api("/video/{$videoId}/upload", 'POST', $data, $headers);
    }
    
}
