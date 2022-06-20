<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2;

use AS2\Management;
use AS2\MessageRepositoryInterface;
use AS2\PartnerRepositoryInterface;
use AS2\Server;
use lujie\extend\psr\log\Yii2Logger;
use yii\base\BaseObject;
use yii\di\Instance;
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
}