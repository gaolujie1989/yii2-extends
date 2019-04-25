<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\searches;


use lujie\queuing\monitor\models\QueueWorker;

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
    public function rules()
    {
        return [
            [['queue', 'status'], 'safe'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery|\yii\db\QueryInterface
     * @inheritdoc
     */
    public function query()
    {
        $query = static::find()->andFilterWhere(['queue' => $this->queue]);
        if (strlen($this->status)) {
            $query->andWhere([$this->status ? '>' : '=', 'finished_at', 0]);
        }
        return $query;
    }
}
