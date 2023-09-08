<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\account\tasks;

use Generator;
use lujie\common\account\models\Account;

/**
 * Class BaseAccountTask
 * @package lujie\common\account\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait BaseAccountSubTaskTrait
{
    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldSubTask(): bool
    {
        return empty($this->accountNames) && empty($this->accountIds);
    }

    /**
     * @return array|Generator
     * @inheritdoc
     */
    public function createSubTasks(): array|Generator
    {
        $accountQuery = $this->getAccountQuery();
        $progress = $this->getProgress($accountQuery->count());
        foreach ($accountQuery->each() as $account) {
            $progress->done++;
            $this->createAccountSubTask($account);
            yield $progress;
        }
    }

    /**
     * @param Account $account
     * @return $this
     * @inheritdoc
     */
    protected function createAccountSubTask(Account $account): static
    {
        $subTask = new static();
        $subTask->accountIds = [$account->account_id];
        $subTask->id = $this->id . '-' . $account->name;
        $subTask->shouldQueued = true;
        return $subTask;
    }
}
