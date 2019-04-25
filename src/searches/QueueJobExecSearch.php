<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\searches;

use lujie\queuing\monitor\models\QueueJobExec;

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
    public function rules()
    {
        return [
            [['queue', 'job_id', 'worker_pid', 'status'], 'safe'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery|\yii\db\QueryInterface
     * @inheritdoc
     */
    public function query()
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
