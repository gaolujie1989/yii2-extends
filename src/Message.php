<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2;

use AS2\MessageInterface;
use AS2\MimePart;
use AS2\PartnerInterface;
use lujie\as2\models\As2Message;
use lujie\as2\models\As2MessageContent;
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
     * @var As2MessageContent
     */
    private $as2MessageContent;

    /**
     * @param As2Message $as2Message
     * @param As2MessageContent $as2MessageContent
     * @param array $config
     */
    public function __construct(As2Message $as2Message, As2MessageContent $as2MessageContent, array $config = [])
    {
        $this->as2Message = $as2Message;
        $this->as2MessageContent = $as2MessageContent;
        parent::__construct($config);
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function save(): bool
    {
        return $this->as2MessageContent->save(false) && $this->as2Message->save(false);
    }

    public function getMessageId(): ?string
    {
        return $this->as2Message->message_id;
    }

    public function setMessageId($id): void
    {
        $this->as2Message->message_id = $id;
        $this->as2MessageContent->message_id = $id;
    }

    public function getDirection(): ?int
    {
        return $this->as2Message->direction;
    }

    public function setDirection($dir): void
    {
        $this->as2Message->direction = $dir;
    }

    public function getSender(): ?Partner
    {
        $sender = $this->as2Message->sender;
        return $sender ? new Partner($sender) : null;
    }

    public function setSender(PartnerInterface $partner): void
    {
        $this->as2Message->sender_id = $partner->getAs2Id();
    }

    public function getReceiver(): ?Partner
    {
        $receiver = $this->as2Message->receiver;
        return $receiver ? new Partner($receiver) : null;
    }

    public function setReceiver(PartnerInterface $partner): void
    {
        $this->as2Message->receiver_id = $partner->getAs2Id();
    }

    public function getHeaders(): ?string
    {
        return $this->as2MessageContent->headers;
    }

    public function setHeaders($headers): void
    {
        $this->as2MessageContent->headers = $headers;
    }

    public function getPayload(): ?string
    {
        return $this->as2MessageContent->payload;
    }

    public function setPayload($payload): void
    {
        $this->as2MessageContent->payload = $payload instanceof MimePart ? $payload->toString() : $payload;
    }

    public function getStatus(): ?string
    {
        return $this->as2Message->status;
    }

    public function setStatus($status): void
    {
        $this->as2Message->status = $status;
    }

    public function getStatusMsg(): ?string
    {
        return $this->as2Message->status_msg;
    }

    public function setStatusMsg($msg): void
    {
        $this->as2Message->status_msg = $msg;
    }

    public function getMdnStatus(): ?string
    {
        return $this->as2Message->mdn_status;
    }

    public function setMdnStatus($status): void
    {
        $this->as2Message->mdn_status = $status;
    }

    public function getMdnPayload(): ?string
    {
        return $this->as2MessageContent->mdn_payload;
    }

    public function setMdnPayload($mdn): void
    {
        $this->as2MessageContent->mdn_payload = $mdn instanceof MimePart ? $mdn->toString() : $mdn;
    }

    public function getMdnMode(): ?string
    {
        return $this->as2Message->mdn_mode;
    }

    public function setMdnMode($mode): void
    {
        $this->as2Message->mdn_mode = $mode;
    }

    public function getMic(): ?string
    {
        return $this->as2Message->mic;
    }

    public function setMic($mic): void
    {
        $this->as2Message->mic = $mic;
    }

    public function getSigned(): bool
    {
        return (bool)$this->as2Message->signed;
    }

    public function setSigned($val = true): void
    {
        $this->as2Message->signed = $val ? 1 : 0;
    }

    public function getEncrypted(): bool
    {
        return (bool)$this->as2Message->encrypted;
    }

    public function setEncrypted($val = true): void
    {
        $this->as2Message->encrypted = $val ? 1 : 0;
    }

    public function getCompressed(): bool
    {
        return (bool)$this->as2Message->compressed;
    }

    public function setCompressed($val = true): void
    {
        $this->as2Message->compressed = $val ? 1 : 0;
    }
}