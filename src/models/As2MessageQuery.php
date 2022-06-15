<?php

namespace lujie\as2\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[As2Message]].
 *
 * @method As2MessageQuery id($id)
 * @method As2MessageQuery orderById($sort = SORT_ASC)
 * @method As2MessageQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method As2MessageQuery messageId($messageId)
 * @method As2MessageQuery messageType($messageType)
 * @method As2MessageQuery senderId($senderId)
 * @method As2MessageQuery receiverId($receiverId)
 * @method As2MessageQuery status($status)
 * @method As2MessageQuery mdnStatus($mdnStatus)
 *
 * @method As2MessageQuery createdAtBetween($from, $to = null)
 * @method As2MessageQuery updatedAtBetween($from, $to = null)
 *
 * @method As2MessageQuery orderByMessageId($sort = SORT_ASC)
 * @method As2MessageQuery orderBySenderId($sort = SORT_ASC)
 * @method As2MessageQuery orderByReceiverId($sort = SORT_ASC)
 * @method As2MessageQuery orderByCreatedAt($sort = SORT_ASC)
 * @method As2MessageQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method As2MessageQuery indexByMessageId()
 * @method As2MessageQuery indexBySenderId()
 * @method As2MessageQuery indexByReceiverId()
 *
 * @method array getMessageIds()
 * @method array getSenderIds()
 * @method array getReceiverIds()
 *
 * @method array|As2Message[] all($db = null)
 * @method array|As2Message|null one($db = null)
 * @method array|As2Message[] each($batchSize = 100, $db = null)
 *
 * @see As2Message
 */
class As2MessageQuery extends \yii\db\ActiveQuery
{

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'messageId' => 'message_id',
                    'messageType' => 'message_type',
                    'senderId' => 'sender_id',
                    'receiverId' => 'receiver_id',
                    'status' => 'status',
                    'mdnStatus' => 'mdn_status',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByMessageId' => 'message_id',
                    'orderBySenderId' => 'sender_id',
                    'orderByReceiverId' => 'receiver_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByMessageId' => 'message_id',
                    'indexBySenderId' => 'sender_id',
                    'indexByReceiverId' => 'receiver_id',
                ],
                'queryReturns' => [
                    'getMessageIds' => ['message_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getSenderIds' => ['sender_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getReceiverIds' => ['receiver_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
