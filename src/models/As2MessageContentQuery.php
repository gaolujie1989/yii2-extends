<?php

namespace lujie\as2\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[As2MessageContent]].
 *
 * @method As2MessageContentQuery id($id)
 * @method As2MessageContentQuery orderById($sort = SORT_ASC)
 * @method As2MessageContentQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method As2MessageContentQuery messageId($messageId)
 *
 * @method As2MessageContentQuery createdAtBetween($from, $to = null)
 * @method As2MessageContentQuery updatedAtBetween($from, $to = null)
 *
 * @method As2MessageContentQuery orderByMessageId($sort = SORT_ASC)
 * @method As2MessageContentQuery orderByCreatedAt($sort = SORT_ASC)
 * @method As2MessageContentQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method As2MessageContentQuery indexByMessageId()
 *
 * @method array getMessageIds()
 *
 * @method array|As2MessageContent[] all($db = null)
 * @method array|As2MessageContent|null one($db = null)
 * @method array|As2MessageContent[] each($batchSize = 100, $db = null)
 *
 * @see As2MessageContent
 */
class As2MessageContentQuery extends \yii\db\ActiveQuery
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
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByMessageId' => 'message_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByMessageId' => 'message_id',
                ],
                'queryReturns' => [
                    'getMessageIds' => ['message_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}
