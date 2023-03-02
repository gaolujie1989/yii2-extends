<?php

namespace lujie\dpd;

use Http\Promise\Promise;
use Phpro\SoapClient\Middleware\Middleware;
use Psr\Http\Message\RequestInterface;
use function GuzzleHttp\Psr7\stream_for;

class DpdSoapAuthMiddleware extends Middleware
{
    /**
     * @var string
     */
    private $delisId;

    /**
     * @var string
     */
    private $authToken;

    /**
     * @var string
     */
    private $messageLanguage;

    public function __construct(string $delisId, string $authToken, string $messageLanguage)
    {
        $this->delisId = $delisId;
        $this->authToken = $authToken;
        $this->messageLanguage = $messageLanguage;
    }

    public function getName(): string
    {
        return 'dpd_auth_middleware';
    }

    public function beforeRequest(callable $handler, RequestInterface $request): Promise
    {
        $headerNs = 'xmlns:ns="http://dpd.com/common/service/types/Authentication/2.0"';
        $headerXml = '<SOAP-ENV:Header><ns:authentication>'
            . "<delisId>{$this->delisId}</delisId>"
            . "<authToken>{$this->authToken}</authToken>"
            . "<messageLanguage>{$this->messageLanguage}</messageLanguage>"
            . '</ns:authentication></SOAP-ENV:Header>';
        //大傻叉，SoapRequest转换成PsrRequest的时候，BodyStream没有reset
        $bodyStream = $request->getBody();
        $bodyStream->rewind();
        $soapXml = $bodyStream->getContents();
        $soapXml = strtr($soapXml, [
            'xmlns:ns1' => $headerNs . ' xmlns:ns1',
            '<SOAP-ENV:Body>' => $headerXml . '<SOAP-ENV:Body>',
        ]);
        codecept_debug($soapXml);
        $request = $request->withBody(stream_for($soapXml));

        return $handler($request);
    }
}
