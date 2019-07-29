<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\searches;

use lujie\queuing\monitor\models\QueueJob;
use yii\db\ActiveQuery;
use yii\di\Instance;
use yii\helpers\VarDumper;
use yii\queue\Queue;

/**
 * Class QueueJobSearch
 * @package lujie\queuing\monitor\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueueJobSearch extends QueueJob
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['queue', 'job_id', 'last_exec_status'], 'safe'],
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
            'last_exec_status' => $this->last_exec_status,
        ]);
        return $query;
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getJobInfo(): ?string
    {
        if (empty($this->queue)) {
            return null;
        }
        /** @var Queue $queue */
        $queue = Instance::ensure($this->queue);
        $job = $queue->serializer->unserialize($this->job);
        return VarDumper::dumpAsString($job);
    }

    /**
     * @return mixed
     * @inheritdoc
     */
    public function fields(): array
    {
        $fields = parent::fields();
        unset($fields['job']);
        return $fields;
    }

    /**
     * @return array|false
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'jobInfo' => 'jobInfo'
        ]);
    }
}
