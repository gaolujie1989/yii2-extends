<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\rules;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Class QueryConditionRule
 * 数据权限，行数据过滤规则
 * @package lujie\auth\rules
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueryResultRule extends Rule
{
    /**
     * @var string
     */
    public $name = 'QueryResult';

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
        Yii::configure($this, $item->data['rule'] ?? []);
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