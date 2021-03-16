<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\account\searches;

use lujie\common\account\models\Account;
use lujie\common\account\models\AccountQuery;
use lujie\extend\db\SearchTrait;
use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use yii\db\ActiveQueryInterface;

/**
 * Class AccountSearch
 * @package lujie\common\account\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AccountSearch extends Account
{
    use SearchTrait;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return array_merge(ModelHelper::searchRules($this), [
            [['username'], 'string'],
        ]);
    }

    /**
     * @return ActiveQueryInterface|AccountQuery
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = ModelHelper::query($this);
        QueryHelper::filterValue($query, $this->getAttributes(['username']), true);
        return $query;
    }
}