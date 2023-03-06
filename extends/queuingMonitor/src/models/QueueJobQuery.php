<?php

namespace lujie\queuing\monitor\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[QueueJob]].
 *
 * @method QueueJobQuery id($id)
 * @method QueueJobQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method QueueJobQuery queueJobId($queueJobId)
 * @method QueueJobQuery jobId($jobId)
 * @method QueueJobQuery lastExecStatus($lastExecStatus)
 *
 * @method array|QueueJob[] all($db = null)
 * @method array|QueueJob|null one($db = null)
 * @method array|QueueJob[] each($batchSize = 100, $db = null)
 *
 * @see QueueJob
 */
class QueueJobQuery extends \yii\db\ActiveQuery
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
                    'queueJobId' => 'queue_job_id',
                    'jobId' => 'job_id',
                    'lastExecStatus' => 'last_exec_status',
                ]
            ]
        ];
    }
}
