<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2;

use AS2\MessageInterface;
use AS2\PartnerInterface;
use lujie\as2\models\As2Message;
use yii\base\BaseObject;

/**
 * Class Message
 * @package lujie\as2
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Message extends BaseObject implements MessageInterface
{
    /**
     * @var As2Message
     */
    private $as2Message;

    /**
     * @param As2Message $as2Message
     * @param array $config
     */
    public function __construct(As2Message $as2Message, array $config = [])
    {
        $this->as2Message = $as2Message;
        parent::__construct($config);
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function save(): bool
    {
        return $this->as2Message->save();
    }

    public function getMessageId()
    {
        return $this->as2Message->message_id;
    }

    public function setMessageId($id)
    {
        // TODO: Implement setMessageId() method.
    }

    public function getDirection()
    {
        // TODO: Implement getDirection() method.
    }

    public function setDirection($dir)
    {
        // TODO: Implement setDirection() method.
    }

    public function getSender()
    {
        // TODO: Implement getSender() method.
    }

    public function setSender(PartnerInterface $partner)
    {
        // TODO: Implement setSender() method.
    }

    public function getReceiver()
    {
        // TODO: Implement getReceiver() method.
    }

    public function setReceiver(PartnerInterface $partner)
    {
        // TODO: Implement setReceiver() method.
    }

    public function getHeaders()
    {
        // TODO: Implement getHeaders() method.
    }

    public function setHeaders($headers)
    {
        // TODO: Implement setHeaders() method.
    }

    public function getPayload()
    {
        // TODO: Implement getPayload() method.
    }

    public function setPayload($payload)
    {
        // TODO: Implement setPayload() method.
    }

    public function getStatus()
    {
        // TODO: Implement getStatus() method.
    }

    public function setStatus($status)
    {
        // TODO: Implement setStatus() method.
    }

    public function getStatusMsg()
    {
        // TODO: Implement getStatusMsg() method.
    }

    public function setStatusMsg($msg)
    {
        // TODO: Implement setStatusMsg() method.
    }

    public function getMdnStatus()
    {
        // TODO: Implement getMdnStatus() method.
    }

    public function setMdnStatus($status)
    {
        // TODO: Implement setMdnStatus() method.
    }

    public function getMdnPayload()
    {
        // TODO: Implement getMdnPayload() method.
    }

    public function setMdnPayload($mdn)
    {
        // TODO: Implement setMdnPayload() method.
    }

    public function getMdnMode()
    {
        // TODO: Implement getMdnMode() method.
    }

    public function setMdnMode($mode)
    {
        // TODO: Implement setMdnMode() method.
    }

    public function getMic()
    {
        // TODO: Implement getMic() method.
    }

    public function setMic($mic)
    {
        // TODO: Implement setMic() method.
    }

    public function getSigned()
    {
        // TODO: Implement getSigned() method.
    }

    public function setSigned($val = true)
    {
        // TODO: Implement setSigned() method.
    }

    public function getEncrypted()
    {
        // TODO: Implement getEncrypted() method.
    }

    public function setEncrypted($val = true)
    {
        // TODO: Implement setEncrypted() method.
    }

    public function getCompressed()
    {
        // TODO: Implement getCompressed() method.
    }

    public function setCompressed($val = true)
    {
        // TODO: Implement setCompressed() method.
    }
}