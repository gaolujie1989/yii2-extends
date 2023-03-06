<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2;

use AS2\MessageInterface;
use AS2\MessageRepositoryInterface;
use lujie\as2\models\As2Message;
use lujie\as2\models\As2MessageContent;
use yii\base\BaseObject;
use yii\base\Component;
use yii\base\Event;
use yii\base\NotSupportedException;

/**
 * Class MessageRepository
 * @package lujie\as2
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MessageRepository extends Component implements MessageRepositoryInterface
{
    public const EVENT_AFTER_MESSAGE_SAVED = 'afterMessageSaved';

    /**
     * @param string $id
     * @return MessageInterface|null
     * @inheritdoc
     */
    public function findMessageById($id): ?MessageInterface
    {
        $as2Message = As2Message::find()->messageId($id)->one();
        return $as2Message ? new Message($as2Message, $as2Message->content) : null;
    }

    /**
     * @param array $data
     * @return MessageInterface
     * @inheritdoc
     */
    public function createMessage($data = []): MessageInterface
    {
        return new Message(new As2Message(), new As2MessageContent(), $data);
    }

    /**
     * @param MessageInterface $message
     * @return bool
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function saveMessage(MessageInterface $message): bool
    {
        if ($message instanceof Message) {
            if ($message->save()) {
                $this->afterMessageSaved($message);
                return true;
            }
            return false;
        }
        throw new NotSupportedException('Unknown message');
    }

    /**
     * @param MessageInterface $message
     * @inheritdoc
     */
    public function afterMessageSaved(MessageInterface $message): void
    {
        $event = new MessageEvent();
        $event->message = $message;
        $this->trigger(self::EVENT_AFTER_MESSAGE_SAVED, $event);
    }
}