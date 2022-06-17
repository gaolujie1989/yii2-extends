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
use yii\base\NotSupportedException;

/**
 * Class MessageRepository
 * @package lujie\as2
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MessageRepository extends BaseObject implements MessageRepositoryInterface
{
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
     * @return bool|void
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function saveMessage(MessageInterface $message)
    {
        if ($message instanceof Message) {
            $message->save();
        }
        throw new NotSupportedException('Unknown message');
    }
}