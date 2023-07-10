<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\log\searches;

use lujie\extend\db\SearchTrait;
use lujie\extend\helpers\QueryHelper;
use lujie\extend\log\models\Log;
use yii\db\ActiveQueryInterface;

/**
 * Class LogSearch
 * @package lujie\extend\log\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class LogSearch extends Log
{
    use SearchTrait;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge($this->searchRules(), [
            [['level', 'category', 'prefix', 'message', 'summary'], 'safe'],
        ]);
    }

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = $this->searchQuery();
        QueryHelper::filterValue($query, $this->getAttributes(['level']));
        QueryHelper::filterValue($query, $this->getAttributes(['category']), 'L');
        QueryHelper::filterValue($query, $this->getAttributes(['prefix', 'message', 'summary']), true);
        return $query;
    }
}
