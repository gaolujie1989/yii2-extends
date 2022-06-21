<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2;

use AS2\Management;
use AS2\MessageRepositoryInterface;
use AS2\MimePart;
use AS2\PartnerRepositoryInterface;
use AS2\Server;
use lujie\as2\models\As2Message;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\psr\log\Yii2Logger;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\di\Instance;
use yii\helpers\Json;
use yii\web\Response;

/**
 * Class As2Manager
 * @package lujie\as2
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class As2Manager extends BaseObject
{
    /**
     * @var Management
     */
    public $management = ['class' => \lujie\as2\Management::class];

    /**
     * @var PartnerRepositoryInterface
     */
    public $partnerRepository = PartnerRepository::class;

    /**
     * @var MessageRepositoryInterface
     */
    public $messageRepository = MessageRepository::class;

    /**
     * @var array
     */
    public $messageProcessors = [];

    /**
     * @var Server
     */
    private $_server;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->management = Instance::ensure($this->management, Management::class);
        $this->management->setLogger(new Yii2Logger(['category' => __CLASS__]));
        $this->partnerRepository = Instance::ensure($this->partnerRepository, PartnerRepositoryInterface::class);
        $this->messageRepository = Instance::ensure($this->messageRepository, MessageRepositoryInterface::class);
    }

    /**
     * @return Server
     * @inheritdoc
     */
    protected function getServer(): Server
    {
        if (!$this->_server) {
            $this->_server = new Server($this->management, $this->partnerRepository, $this->messageRepository);
        }
        return $this->_server;
    }

    /**
     * @return Response
     * @inheritdoc
     */
    public function handleRequest(): Response
    {
        $response = $this->getServer()->execute();

        $yiiResponse = new Response();
        $yiiResponse->statusCode = $response->getStatusCode();
        $headerCollection = $yiiResponse->getHeaders();
        $headers = $response->getHeaders();
        foreach ($headers as $name => $value) {
            $headerCollection->add($name, $value);
        }
        if ($body = $response->getBody()) {
            $yiiResponse->content = $body->getContents();
        }
        return $yiiResponse;
    }

    /**
     * @param As2Message $as2Message
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function processMessage(As2Message $as2Message): bool
    {
        $messageHandler = $this->getMessageProcessor($as2Message->sender->partner_type);
        $mimePart = MimePart::fromString($as2Message->content->payload);
        if ($messageHandler->process($mimePart->getBody())) {
            $as2Message->process_status = ExecStatusConst::EXEC_STATUS_SUCCESS;
        } else {
            $as2Message->process_status = ExecStatusConst::EXEC_STATUS_FAILED;
            $as2Message->process_status_msg = Json::encode($messageHandler->getErrors());
        }
        return $as2Message->save(false);
    }

    /**
     * @param string $partnerType
     * @return As2MessageProcessorInterface
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getMessageProcessor(string $partnerType): As2MessageProcessorInterface
    {
        if (empty($this->messageProcessors[$partnerType])) {
            throw new InvalidArgumentException("Processor of {$partnerType} not set");
        }
        if (!$this->messageProcessors[$partnerType] instanceof As2MessageProcessorInterface) {
            $this->messageProcessors[$partnerType] = Instance::ensure($this->messageProcessors[$partnerType], As2MessageProcessorInterface::class);
        }
        return $this->messageProcessors[$partnerType];
    }
}