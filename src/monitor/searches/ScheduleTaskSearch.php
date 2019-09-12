<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\monitor\searches;


use lujie\scheduling\monitor\models\ScheduleTask;
use yii\db\ActiveQuery;

/**
 * Class ScheduleTaskSearch
 * @package lujie\scheduling\monitor\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ScheduleTaskSearch extends ScheduleTask
{
    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['task_code', 'task_group', 'status'], 'safe']
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function query(): ActiveQuery
    {
        return static::find()->andFilterWhere([
            'task_code' => $this->task_code,
            'task_group' => $this->task_group,
            'status' => $this->status,
        ])->addOrderBy(['position' => SORT_ASC]);
    }
}
