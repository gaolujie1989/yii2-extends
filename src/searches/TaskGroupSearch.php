<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\searches;


use lujie\project\constants\GlobalStatusConst;
use lujie\project\models\Project;
use lujie\project\models\ProjectQuery;
use lujie\project\models\TaskGroup;
use lujie\project\models\TaskGroupQuery;
use yii\db\Query;

/**
 * Class TaskGroupSearch
 * @package lujie\project\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskGroupSearch extends TaskGroup
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['project_id'], 'safe'],
        ];
    }

    /**
     * @return TaskGroupQuery
     * @inheritdoc
     */
    public function query(): TaskGroupQuery
    {
        return static::find()
            ->andFilterWhere(['project_id' => $this->project_id]);
    }
}
