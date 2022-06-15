<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2;

use AS2\MessageInterface;
use AS2\MessageRepositoryInterface;
use lujie\as2\models\As2Message;
use yii\base\BaseObject;
use yii\base\NotSupportedException;

/**
 * Class MessageRepository
 * @package lujie\as2
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MessageRepository extends BaseObject implements MessageRepositoryInterface
{
    public function findMessageById($id)
    {
        $as2Message = As2Message::find()->messageId($id)->one();
        return $as2Message ? new Message($as2Message) : null;
    }

    public function createMessage($data = [])
    {
        return new Message(new As2Message($data));
    }

    public function saveMessage(MessageInterface $message)
    {
        if ($message instanceof Message) {
            $message->save();
        }
        throw new NotSupportedException('Unknown message');
    }
}