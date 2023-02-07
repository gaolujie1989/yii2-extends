<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;

use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentAccountQuery;
use lujie\scheduling\CronTask;

/**
 * Class BaseFulfillmentTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseFulfillmentTask extends CronTask
{
    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

    /**
     * @var array
     */
    public $accountNames = [];

    /**
     * @return FulfillmentAccountQuery
     * @inheritdoc
     */
    protected function getAccountQuery(): FulfillmentAccountQuery
    {
        $accountQuery = FulfillmentAccount::find();
        if ($this->accountNames) {
            $accountQuery->name($this->accountNames);
        } else {
            $accountQuery->active();
        }
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
