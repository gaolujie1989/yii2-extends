<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\searches;

use lujie\project\constants\GlobalStatusConst;
use lujie\project\models\Task;
use lujie\project\models\TaskQuery;

/**
 * Class TaskSearch
 * @package lujie\project\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskSearch extends Task
{
    /**
     * @var string
     */
    public $globalStatus;

    /**
     * @var bool
     */
    public $isSubTask;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['project_id', 'task_group_id', 'parent_task_id',
                'position', 'priority', 'status', 'owner_id', 'executor_id',
                'globalStatus', 'isSubTask'], 'safe'],
            [['due_at', 'started_at', 'finished_at'], 'each', 'rule' => ['date']],
        ];
    }

    /**
     * @return TaskQuery
     * @inheritdoc
     */
    public function query(): TaskQuery
    {
        $query = static::find()->andFilterWhere(['LIKE', 'name', $this->name])
            ->andFilterWhere($this->getAttributes([
                'project_id', 'task_group_id', 'parent_task_id',
                'priority', 'status', 'owner_id', 'executor_id'
            ]));

        $timeAttributes = ['due_at', 'started_at', 'finished_at'];
        foreach ($timeAttributes as $timeAttribute) {
            $value = $this->getAttribute($timeAttribute);
            if ($value && is_array($value)) {
//                $query->andFilterWhere(['BETWEEN', $timeAttribute, $value[0], $value[1]]);
                $query->andFilterWhere(['>=', $timeAttribute, $value[0]])
                    ->andFilterWhere(['<=', $timeAttribute, $value[1]]);
            }
        }

        switch ($this->globalStatus) {
            case GlobalStatusConst::STATUS_NORMAL:
                $query->normal();
                break;
            case GlobalStatusConst::STATUS_ARCHIVED:
                $query->archived();
                break;
            case GlobalStatusConst::STATUS_DELETED:
                $query->deleted();
                break;
        }

        if ($this->isSubTask !== null && $this->isSubTask !== '') {
            $query->isSubTask($this->isSubTask);
        }

        return $query;
    }
}
