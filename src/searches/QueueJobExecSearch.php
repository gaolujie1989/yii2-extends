<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\searches;

use lujie\queuing\monitor\models\QueueJobExec;
use yii\db\ActiveQuery;

/**
 * Class QueueJobExecSearch
 * @package lujie\queuing\monitor\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueueJobExecSearch extends QueueJobExec
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['queue', 'job_id', 'worker_pid', 'status'], 'safe'],
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function query(): ActiveQuery
    {
        $query = static::find()->andFilterWhere([
            'queue' => $this->queue,
            'job_id' => $this->job_id,
            'worker_pid' => $this->worker_pid,
            'status' => $this->status,
        ]);
        return $query;
    }
}
