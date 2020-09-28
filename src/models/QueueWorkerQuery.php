<?php

namespace lujie\queuing\monitor\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[QueueWorker]].
 *
 * @method QueueWorkerQuery id($id)
 * @method QueueWorkerQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method QueueWorkerQuery queueWorkerId($queueWorkerId)
 *
 * @method array|QueueWorker[] all($db = null)
 * @method array|QueueWorker|null one($db = null)
 * @method array|QueueWorker[] each($batchSize = 100, $db = null)
 *
 * @see QueueWorker
 */
class QueueWorkerQuery extends \yii\db\ActiveQuery
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
                    'queueWorkerId' => 'queue_worker_id',
                ]
            ]
        ];
    }

}
