<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\history\searches;

use lujie\common\history\models\ModelHistory;
use lujie\extend\db\SearchTrait;
use lujie\extend\helpers\QueryHelper;
use yii\db\ActiveQueryInterface;

/**
 * Class ModelHistorySearch
 * @package lujie\common\history\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelHistorySearch extends ModelHistory
{
    use SearchTrait;

    public $history_attribute;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge($this->searchRules(), [
            [['attribute'], 'string'],
        ]);
    }

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = $this->searchQuery();
        if ($this->history_attribute) {
            $query->innerJoinWith(['details']);
            QueryHelper::filterValue($query, ['changed_attribute' => $this->history_attribute]);
        }
        return $query;
    }
}
