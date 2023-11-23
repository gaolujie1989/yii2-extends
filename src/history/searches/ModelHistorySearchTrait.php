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
 * Trait ModelHistorySearchTrait
 * @package lujie\common\history\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait ModelHistorySearchTrait
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
            [['history_attribute'], 'string'],
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
