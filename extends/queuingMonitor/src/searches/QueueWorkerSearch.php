<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\searches;

use lujie\queuing\monitor\models\QueueWorker;
use yii\db\ActiveQuery;

/**
 * Class QueueWorkerSearch
 * @package lujie\queuing\monitor\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueueWorkerSearch extends QueueWorker
{
    public $status;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['queue', 'status'], 'safe'],
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function query(): ActiveQuery
    {
        $query = static::find()->andFilterWhere(['queue' => $this->queue]);
        if ($this->status !== null && $this->status !== '') {
            $query->andWhere([$this->status ? '>' : '=', 'finished_at', 0]);
        }
        return $query;
    }
}
