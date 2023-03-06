<?php

namespace lujie\queuing\monitor\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[QueueJobExec]].
 *
 * @method QueueJobExecQuery id($id)
 * @method QueueJobExecQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method QueueJobExecQuery jobExecId($jobExecId)
 * @method QueueJobExecQuery jobId($jobId)
 * @method QueueJobExecQuery status($status)
 *
 * @method array|QueueJobExec[] all($db = null)
 * @method array|QueueJobExec|null one($db = null)
 * @method array|QueueJobExec[] each($batchSize = 100, $db = null)
 *
 * @see QueueJobExec
 */
class QueueJobExecQuery extends \yii\db\ActiveQuery
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
                    'jobExecId' => 'job_exec_id',
                    'jobId' => 'job_id',
                    'status' => 'status',
                ]
            ]
        ];
    }
}
