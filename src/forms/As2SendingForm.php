<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2\forms;

use lujie\extend\flysystem\Filesystem;
use lujie\as2\As2Manager;
use lujie\upload\behaviors\FileTrait;
use Yii;
use yii\base\Model;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

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

    public $files;

    /**
     * @var ?Filesystem
     */
    public $fs;

    /**
     * @var string
     */
    public $path = '@statics/uploads';

    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->initFsAndPath();
        $this->as2Manager = Instance::ensure($this->as2Manager, As2Manager::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['files'], 'formatFiles'],
            [['files'], 'required'],
            [['files'], 'validateFilesExist'],
            [['sender_id', 'receiver_id'], 'required'],
            [['sender_id', 'receiver_id'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function formatFiles(): void
    {
        $files = $this->files;
        if ($files && is_array($files) && is_array(reset($files))) {
            $this->files = array_filter(ArrayHelper::getColumn($files, 'file'));
        } else if ($files && !is_array($files)) {
            $this->files = [$files];
        }
    }

    /**
     * @inheritdoc
     */
    public function validateFilesExist(): void
    {
        foreach ($this->files as $file) {
            if (!$this->existFile($file)) {
                $this->addError('files', Yii::t('lujie/import', 'Send file not exists.'));
            }
        }
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
        $this->message_id = $message->getMessageId();

        $filePath = $this->path . reset($this->files);
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
