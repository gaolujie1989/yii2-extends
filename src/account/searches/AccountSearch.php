<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\account\searches;

use lujie\common\account\models\Account;
use lujie\common\account\models\AccountQuery;

/**
 * Class AccountSearch
 * @package lujie\common\account\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AccountSearch extends Account
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'type', 'username', 'status'], 'safe'],
        ];
    }

    /**
     * @return AccountQuery
     * @inheritdoc
     */
    public function query(): AccountQuery
    {
        return static::find()
            ->andFilterWhere([
                'type' => $this->type,
                'status' => $this->status,
            ])
            ->andFilterWhere(['LIKE', 'name', $this->name])
            ->andFilterWhere(['LIKE', 'username', $this->username]);
    }
}