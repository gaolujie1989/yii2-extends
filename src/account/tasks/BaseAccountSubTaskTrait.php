<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\account\tasks;

use Generator;
use lujie\common\account\models\Account;
use lujie\executing\Progress;

/**
 * Trait BaseAccountSubTaskTrait
 *
 * @property $subTaskDelay = 3
 *
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
        $subTaskDelay = $this->subTaskDelay ?? 3;
        $delay = 0;
        $accountQuery = $this->getAccountQuery();
        $progress = $this->getProgress($accountQuery->count());
        foreach ($accountQuery->each() as $account) {
            $progress->done++;
            $this->createAccountSubTask($account, $delay += $subTaskDelay);
            yield true;
        }
    }

    /**
     * @param Account $account
     * @param int|null $delay
     * @return static
     * @inheritdoc
     */
    protected function createAccountSubTask(Account $account, ?int $delay = null): static
    {
        $subTask = new static();
        $subTask->accountIds = [$account->account_id];
        $subTask->id = $this->id . '-' . $account->name;
        $subTask->shouldQueued = true;
        $subTask->delay = $delay;
        return $subTask;
    }
}
