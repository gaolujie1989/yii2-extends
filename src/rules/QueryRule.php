<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\rules;

use yii\data\ActiveDataProvider;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Class QueryConditionRule
 * @package lujie\auth\rules
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueryRule extends Rule
{
    /**
     * @var string
     */
    public $name = 'QueryFilter';

    /**
     * @var array
     */
    public $condition = [];

    /**
     * @param int|string $user
     * @param Item $item
     * @param array $params
     * @return bool
     * @inheritdoc
     */
    public function execute($user, $item, $params): bool
    {
        if (empty($params['result'])) {
            return true;
        }
        $result = $params['result'];
        if ($result instanceof ActiveDataProvider) {
            $result->query->andFilterWhere($this->condition);
        }
        return true;
    }
}