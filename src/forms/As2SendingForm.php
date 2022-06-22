<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2\forms;

use lujie\as2\As2Manager;
use lujie\upload\behaviors\FileTrait;
use Yii;
use yii\base\Model;
use yii\di\Instance;

/**
 * Class As2SendingForm
 * @package lujie\as2\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class As2SendingForm extends Model
{
    use FileTrait;

    /**
     * @var As2Manager
     */
    public $as2Manager = 'as2Manager';

    public $sender_id;

    public $receiver_id;

    public $message_id;

    public $file;

    /**
     * @var string
     */
    public $path = '@statics/uploads';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->as2Manager = Instance::ensure($this->as2Manager, As2Manager::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['sender_id', 'receiver_id', 'file'], 'required'],
            [['sender_id', 'receiver_id', 'file'], 'string'],
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function send(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $messageRepository = $this->as2Manager->messageRepository;
        $message = $messageRepository->createMessage();
        $message->setSender($this->as2Manager->partnerRepository->findPartnerById($this->sender_id));
        $message->setReceiver($this->as2Manager->partnerRepository->findPartnerById($this->receiver_id));
        $message->setMessageId(Yii::$app->security->generateRandomString());
        $messageRepository->saveMessage($message);

        $filePath = $this->path . $this->file;
        $as2Message = $this->as2Manager->management->buildMessageFromFile($message, $filePath);
        $messageRepository->saveMessage($message);

        $this->as2Manager->management->sendMessage($message, $as2Message);
        $messageRepository->saveMessage($message);
        return true;
    }

    /**
     * @return string[]
     * @inheritdoc
     */
    public function fields(): array
    {
        return [
            'sender_id' => 'sender_id',
            'receiver_id' => 'receiver_id',
            'message_id' => 'message_id',
        ];
    }
}