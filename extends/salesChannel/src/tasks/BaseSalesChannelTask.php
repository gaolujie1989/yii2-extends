<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelAccountQuery;
use lujie\sales\channel\SalesChannelManager;
use lujie\scheduling\CronTask;

/**
 * Class BaseSalesChannelTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseSalesChannelTask extends CronTask
{
    /**
     * @var SalesChannelManager
     */
    public $salesChannelManager = 'salesChannelManager';

    /**
     * @var array
     */
    public $accountNames = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function getParams(): array
    {
        return array_merge(['accountNames'], parent::getParams());
    }

    /**
     * @return SalesChannelAccountQuery
     * @inheritdoc
     */
    protected function getAccountQuery(): SalesChannelAccountQuery
    {
        $accountQuery = SalesChannelAccount::find();
        if ($this->accountNames) {
            $accountQuery->name($this->accountNames);
        } else {
            $accountQuery->active();
        }
        return $accountQuery;
    }

    /**
     * @return \Generator
     * @inheritdoc
     */
    protected function getAccountIds(): \Generator
    {
        $accountQuery = $this->getAccountQuery()->select(['account_id'])->asArray();
        foreach ($accountQuery->each() as $account) {
            yield $account['account_id'];
        }
    }
}
