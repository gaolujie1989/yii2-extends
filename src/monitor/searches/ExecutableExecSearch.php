<?php

namespace lujie\executing\monitor\searches;

use lujie\executing\monitor\models\ExecutableExec;
use yii\db\ActiveQuery;

/**
 * Class ExecutableExecSearch
 * @package lujie\executing\monitor\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecutableExecSearch extends ExecutableExec
{
    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['executable_id', 'executor', 'status'], 'safe']
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function query(): ActiveQuery
    {
        return static::find()->andFilterWhere([
            'executable_id' => $this->executable_id,
            'executor' => $this->executor,
            'status' => $this->status,
        ])->addOrderBy(['started_at' => SORT_DESC]);
    }
}
