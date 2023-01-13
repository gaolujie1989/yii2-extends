<?php

namespace lujie\sales\channel\controllers\console;

use lujie\executing\Executor;
use lujie\extend\helpers\ValueHelper;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\SalesChannelInterface;
use lujie\sales\channel\SalesChannelManager;
use lujie\sales\channel\tasks\PullSalesChannelOrderTask;
use yii\base\InvalidArgumentException;
use yii\console\Controller;
use yii\di\Instance;
use yii\helpers\VarDumper;

/**
 * Class SalesChannelController
 * @package lujie\sales\channel\controllers\console
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelController extends Controller
{
    /**
     * @var SalesChannelManager
     */
    public $salesChannelManager = 'salesChannelManager';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
    }

    /**
     * @param string $accountName
     * @return SalesChannelAccount
     * @inheritdoc
     */
    protected function getAccount(string $accountName): SalesChannelAccount
    {
        $salesChannelAccount = SalesChannelAccount::find()->name($accountName)->cache()->one();
        if ($salesChannelAccount === null) {
            throw new InvalidArgumentException("Account {$accountName} not found");
        }
        return $salesChannelAccount;
    }

    /**
     * @param string $accountName
     * @return SalesChannelInterface
     * @inheritdoc
     */
    protected function getService(string $accountName): SalesChannelInterface
    {
        $account = $this->getAccount($accountName);
        return $this->salesChannelManager->salesChannelLoader->get($account->account_id);
    }

    #region ITEM

    /**
     * @param string $fulfillmentItemIdsStr
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function actionPushItems(string $salesChannelItemIdsStr): void
    {
        $salesChannelItemIds = ValueHelper::strToArray($salesChannelItemIdsStr);
        $query = SalesChannelItem::find()->salesChannelItemId($salesChannelItemIds);
        foreach ($query->each() as $salesChannelItem) {
            $this->salesChannelManager->pushSalesChannelItem($salesChannelItem);
            VarDumper::dump($salesChannelItem->item_pushed_result);
        }
    }

    #endregion

    #region ORDER

    /**
     * @param string $createdAtFrom
     * @param string $createdAtTo
     * @param int $timeStep
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionPullNewOrders(string $createdAtFrom = '-1 days', string $createdAtTo = 'now', int $timeStep = 43200): void
    {
        /** @var Executor $executor */
        $executor = Instance::ensure('executor', Executor::class);
        $pullSalesChannelOrderTask = new PullSalesChannelOrderTask();
        $pullSalesChannelOrderTask->memoryLimit = '1G';
        $pullSalesChannelOrderTask->timeFrom = $createdAtFrom;
        $pullSalesChannelOrderTask->timeTo = $createdAtTo;
        $pullSalesChannelOrderTask->timeStep = $timeStep;
        $executor->execute($pullSalesChannelOrderTask);
    }

    /**
     * @param $accountName
     * @param $orderIdsStr
     * @inheritdoc
     */
    public function actionPullOrders($accountName, $orderIdsStr): void
    {
        $account = $this->getAccount($accountName);
        $salesChannel = $this->getService($accountName);
        $orderIds = ValueHelper::strToArray($orderIdsStr);
        $salesChannelOrders = SalesChannelOrder::find()->salesChannelAccountId($account->account_id)->orderId($orderIds)->all();
        $salesChannel->pullSalesOrders($salesChannelOrders);
    }

    #endregion
}