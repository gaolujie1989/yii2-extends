<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2;

use AS2\Management;
use AS2\MessageInterface;
use AS2\MessageRepositoryInterface;
use AS2\MimePart;
use AS2\PartnerRepositoryInterface;
use AS2\Server;
use lujie\as2\models\As2Message;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\ExecuteHelper;
use lujie\extend\psr\log\Yii2Logger;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\InvalidArgumentException;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;
use yii\di\Instance;
use yii\helpers\Json;
use yii\web\Response;

/**
 * Class As2Manager
 * @package lujie\as2
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class As2Manager extends BaseObject implements BootstrapInterface
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
     * @var array
     */
    public $allowProcessMessageStatus = [
        MessageInterface::STATUS_SUCCESS,
        MessageInterface::STATUS_IN_PROCESS,
    ];

    /**
     * @var Server
     */
    private $_server;

    /**
     * @param \yii\base\Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(As2Message::class, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterMessageSaved']);
        Event::on(As2Message::class, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterMessageSaved']);
    }

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
     * @param AfterSaveEvent $event
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function afterMessageSaved(AfterSaveEvent $event): void
    {
        /** @var As2Message $as2Message */
        $as2Message = $event->sender;
        if (in_array($as2Message->status, $this->allowProcessMessageStatus, true)) {
            $this->processMessage($as2Message);
        }
    }

    /**
     * @param As2Message $as2Message
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function processMessage(As2Message $as2Message): bool
    {
        return ExecuteHelper::execute(function() use ($as2Message) {
            $messageHandler = $this->getMessageProcessor($as2Message->sender->partner_type);
            $mimePart = MimePart::fromString($as2Message->content->payload);
            return $messageHandler->process($mimePart->getBody());
        }, $as2Message, 'processed_at', 'processed_status', 'processed_result');
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