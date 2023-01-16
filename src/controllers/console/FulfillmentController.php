<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\controllers\console;

use lujie\executing\Executor;
use lujie\extend\helpers\ValueHelper;
use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\FulfillmentServiceInterface;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\tasks\GenerateFulfillmentDailyStockTask;
use yii\base\InvalidArgumentException;
use yii\console\Controller;
use yii\di\Instance;
use yii\helpers\Console;
use yii\helpers\VarDumper;

/**
 * @copyright Copyright (c) 2019
 */
class FulfillmentController extends Controller
{
    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
    }

    /**
     * @param string $accountName
     * @return FulfillmentAccount
     * @inheritdoc
     */
    protected function getAccount(string $accountName): FulfillmentAccount
    {
        $fulfillmentAccount = FulfillmentAccount::find()->name($accountName)->cache()->one();
        if ($fulfillmentAccount === null) {
            throw new InvalidArgumentException("Account {$accountName} not found");
        }
        return $fulfillmentAccount;
    }

    /**
     * @param string $accountName
     * @return FulfillmentServiceInterface
     * @inheritdoc
     */
    protected function getService(string $accountName): FulfillmentServiceInterface
    {
        $account = $this->getAccount($accountName);
        return $this->fulfillmentManager->fulfillmentServiceLoader->get($account->account_id);
    }

    #region PULL

    /**
     * @param int $fulfilmentAccountId
     * @param string $country
     * @inheritdoc
     */
    public function actionPullWarehouses(string $accountName, string $country = 'ES'): void
    {
        $fulfillmentService = $this->getService($accountName);
        $fulfillmentService->pullWarehouses(['country' => $country]);
    }

    /**
     * @param string $accountName
     * @param string $itemIdsStr
     * @inheritdoc
     */
    public function actionPullWarehouseStocks(string $accountName, string $itemIdsStr = ''): void
    {
        $itemIds = array_filter(array_map('trim', explode(',', $itemIdsStr)));
        $account = $this->getAccount($accountName);
        $fulfillmentService = $this->getService($accountName);
        $fulfillmentItems = FulfillmentItem::find()
            ->fulfillmentAccountId($account->account_id)
            ->itemId($itemIds)
            ->all();
        $fulfillmentService->pullWarehouseStocks($fulfillmentItems);
    }

    /**
     * @param int $fulfillmentWarehouseId
     * @param $createdAtFrom
     * @param $createdAtTo
     * @param int $timeStep
     * @inheritdoc
     */
    public function actionPullStockMovements(int $fulfillmentWarehouseId, $createdAtFrom, $createdAtTo, int $timeStep = 86400): void
    {
        $fulfillmentWarehouse = FulfillmentWarehouse::findOne($fulfillmentWarehouseId);
        if ($fulfillmentWarehouse === null) {
            throw new InvalidArgumentException('Invalid fulfillmentWarehouseId');
        }
        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentManager->fulfillmentServiceLoader->get($fulfillmentWarehouse->fulfillment_account_id);

        $createdAtFrom = is_numeric($createdAtFrom) ? $createdAtFrom : strtotime($createdAtFrom);
        $createdAtTo = is_numeric($createdAtTo) ? $createdAtTo : strtotime($createdAtTo);
        Console::startProgress($done = 0, $total = $createdAtTo - $createdAtFrom);
        for ($timeFrom = $createdAtFrom; $timeFrom <= $createdAtTo; $timeFrom += $timeStep) {
            $timeTo = min($timeFrom + $timeStep, $createdAtTo);
            $fulfillmentService->pullWarehouseStockMovements($fulfillmentWarehouse, $timeFrom, $timeTo);
            Console::updateProgress($done = $timeFrom - $createdAtFrom, $total, date('c', $timeFrom));
        }
        Console::endProgress();
    }

    /**
     * @param string $accountName
     * @param string $orderIdsStr
     * @inheritdoc
     */
    public function actionPullOrders(string $accountName, string $orderIdsStr): void
    {
        $account = $this->getAccount($accountName);
        $fulfillmentService = $this->getService($accountName);
        $orderIds = ValueHelper::strToArray($orderIdsStr);
        $fulfillmentOrders = FulfillmentOrder::find()->fulfillmentAccountId($account->account_id)->orderId($orderIds)->all();
        $fulfillmentService->pullFulfillmentOrders($fulfillmentOrders);
    }

    /**
     * @param string $accountName
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionPullFulfillmentCharges(string $accountName): void
    {
        $account = $this->getAccount($accountName);
        $fulfillmentService = $this->getService($accountName);
        $query = FulfillmentOrder::find()
            ->fulfillmentAccountId($account->account_id)
            ->shippingFulfillmentShipped()
            ->chargeNotPulled()
            ->limit(15000);
        Console::startProgress($done = 0, $total = $query->count());
        foreach ($query->batch() as $fulfillmentOrders) {
            Console::updateProgress($done += 50, $total);
            $fulfillmentService->pullFulfillmentCharges($fulfillmentOrders);
        }
        Console::endProgress();
    }

    #endregion

    #region PUSH

    /**
     * @param string $fulfillmentItemIdsStr
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function actionPushItems(string $fulfillmentItemIdsStr): void
    {
        $fulfillmentItemIds = explode(',', $fulfillmentItemIdsStr);
        $query = FulfillmentItem::find()->fulfillmentItemId($fulfillmentItemIds);
        foreach ($query->each() as $fulfillmentItem) {
            $this->fulfillmentManager->pushFulfillmentItem($fulfillmentItem);
            VarDumper::dump($fulfillmentItem->item_pushed_result);
        }
    }

    /**
     * @param string $fulfillmentOrderIdsStr
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function actionPushOrders(string $fulfillmentOrderIdsStr): void
    {
        $fulfillmentOrderIds = explode(',', $fulfillmentOrderIdsStr);
        $query = FulfillmentOrder::find()->fulfillmentOrderId($fulfillmentOrderIds);
        foreach ($query->each() as $fulfillmentOrder) {
            $this->fulfillmentManager->pushFulfillmentOrder($fulfillmentOrder);
            VarDumper::dump($fulfillmentOrder->order_pushed_result);
        }
    }

    /**
     * @param string $fulfillmentOrderIdsStr
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function actionCancelOrders(string $fulfillmentOrderIdsStr): void
    {
        $fulfillmentOrderIds = explode(',', $fulfillmentOrderIdsStr);
        $query = FulfillmentOrder::find()->fulfillmentOrderId($fulfillmentOrderIds);
        foreach ($query->each() as $fulfillmentOrder) {
//            $lockName = $this->fulfillmentManager->mutexNamePrefix . 'cancelFulfillmentOrder:' . $fulfillmentOrder->fulfillment_order_id;
//            $this->fulfillmentManager->mutex->release($lockName);
            $this->fulfillmentManager->cancelFulfillmentOrder($fulfillmentOrder);
            VarDumper::dump($fulfillmentOrder->order_pushed_result);
        }
    }

    #endregion

    /**
     * @param $timeFrom
     * @param $timeTo
     * @param string $executorName
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionGenerateDailyStocks($timeFrom, $timeTo, string $executorName = 'executor'): void
    {
        $dailyStockTask = new GenerateFulfillmentDailyStockTask();
        $dailyStockTask->timeFrom = $timeFrom;
        $dailyStockTask->timeTo = $timeTo;
        if ($executorName) {
            /** @var Executor $executor */
            $executor = Instance::ensure($executorName, Executor::class);
            $executor->execute($dailyStockTask);
        } else {
            $dailyStockTask->execute();
        }
    }
}