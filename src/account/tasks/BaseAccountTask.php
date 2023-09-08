<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\account\tasks;

use Generator;
use lujie\common\account\models\Account;
use lujie\common\account\models\AccountQuery;
use lujie\executing\ProgressInterface;
use lujie\scheduling\CronTask;

/**
 * Class BaseAccountTask
 * @package lujie\common\account\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseAccountTask extends CronTask implements ProgressInterface
{
    use BaseAccountSubTaskTrait;

    /**
     * @var Account
     */
    public $accountClass;

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
