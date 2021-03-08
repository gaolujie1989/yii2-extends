<?php

namespace lujie\executing\monitor\searches;

use lujie\executing\monitor\models\ExecutableExec;
use lujie\extend\base\SearchTrait;
use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use yii\db\ActiveQueryInterface;

/**
 * Class ExecutableExecSearch
 * @package lujie\executing\monitor\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecutableExecSearch extends ExecutableExec
{
    use SearchTrait;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(ModelHelper::searchRules($this), [
            [['executor'], 'safe'],
        ]);
    }

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = ModelHelper::query($this);
        QueryHelper::filterValue($query, $this->getAttributes(['executor']));
        return $query->addOrderBy(['started_at' => SORT_DESC]);
    }
}
