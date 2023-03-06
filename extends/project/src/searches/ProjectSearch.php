<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\searches;

use lujie\extend\db\SearchTrait;
use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use lujie\project\constants\GlobalStatusConst;
use lujie\project\models\Project;
use lujie\project\models\ProjectQuery;
use yii\db\ActiveQueryInterface;

/**
 * Class ProjectSearch
 * @package lujie\project\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ProjectSearch extends Project
{
    use SearchTrait;

    public $globalStatus;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['name', 'visibility', 'owner_id'], 'safe'],
            [['globalStatus'], 'in', 'range' => GlobalStatusConst::STATUS_LIST],
        ];
    }

    /**
     * @return ActiveQueryInterface|ProjectQuery
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = static::find()->notSystem();

        QueryHelper::filterValue($query, $this->getAttributes(['name']), true);
        QueryHelper::filterValue($query, $this->getAttributes(['visibility', 'owner_id']));

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

    /**
     * @param array $row
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        return ModelHelper::prepareArray($row, static::class, [], ['taskGroups', 'tasks']);
    }
}
