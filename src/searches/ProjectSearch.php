<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\searches;


use lujie\project\constants\GlobalStatusConst;
use lujie\project\models\Project;
use lujie\project\models\ProjectQuery;
use yii\db\Query;

/**
 * Class ProjectSearch
 * @package lujie\project\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ProjectSearch extends Project
{
    public $globalStatus;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['name', 'visibility', 'owner_id'], 'safe'],
            [['status'], 'in', 'range' => GlobalStatusConst::STATUS_LIST],
        ];
    }

    /**
     * @return ProjectQuery
     * @inheritdoc
     */
    public function query(): ProjectQuery
    {
        $query = static::find()
            ->notSystem()
            ->andFilterWhere(['LIKE', 'name', $this->name])
            ->andFilterWhere(['visibility' => $this->visibility, 'owner_id' => $this->owner_id]);

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

        return $query;
    }
}
