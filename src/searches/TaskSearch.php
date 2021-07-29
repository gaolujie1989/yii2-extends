<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\searches;

use lujie\extend\db\SearchTrait;
use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use lujie\project\constants\GlobalStatusConst;
use lujie\project\models\Task;
use lujie\project\models\TaskQuery;
use yii\db\ActiveQueryInterface;

/**
 * Class TaskSearch
 * @package lujie\project\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskSearch extends Task
{
    use SearchTrait;

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
        return array_merge($this->searchRules(), [
            [['priority', 'globalStatus', 'isSubTask'], 'safe'],
        ]);
    }

    /**
     * @return ActiveQueryInterface|TaskQuery
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        /** @var TaskQuery $query */
        $query = $this->searchQuery();
        QueryHelper::filterValue($query, $this->getAttributes(['priority']));

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

    /**
     * @param array $row
     * @return array
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        $alias = [
            'due_time' => 'due_at',
            'started_time' => 'started_at',
            'finished_time' => 'finished_at'
        ];
        $relations = ['attachments', 'taskGroup', 'subTasks', 'parentTask'];
        return ModelHelper::prepareArray($row, static::class, $alias, $relations);
    }
}
