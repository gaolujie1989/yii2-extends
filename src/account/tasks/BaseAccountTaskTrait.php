<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\account\tasks;

use Generator;
use lujie\common\account\models\Account;
use lujie\common\account\models\AccountQuery;

/**
 * Class BaseAccountTask
 *
 * @property Account $accountClass
 *
 * @package lujie\common\account\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait BaseAccountTaskTrait
{
    /**
     * @var array
     */
    public $accountNames = [];

    /**
     * @var array
     */
    public $accountIds = [];

    /**
     * @return string[]
     * @inheritdoc
     */
    public function getParams(): array
    {
        return ['accountNames', 'accountIds', 'id'];
    }

    /**
     * @return AccountQuery
     * @inheritdoc
     */
    protected function getAccountQuery(): AccountQuery
    {
        $accountQuery = $this->accountClass::find();
        if ($this->accountNames) {
            $accountQuery->name($this->accountNames);
        } else if ($this->accountIds) {
            $accountQuery->accountId($this->accountIds);
        } else {
            $accountQuery->active();
        }
        return $accountQuery;
    }

    /**
     * @return Generator
     * @inheritdoc
     */
    protected function getAccountIds(): Generator
    {
        $accountQuery = $this->getAccountQuery()->select(['account_id'])->asArray();
        foreach ($accountQuery->each() as $account) {
            yield $account['account_id'];
        }
    }
}
