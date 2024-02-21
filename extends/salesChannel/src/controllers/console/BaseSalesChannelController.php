<?php

namespace lujie\sales\channel\controllers\console;

use lujie\executing\Executor;
use lujie\extend\helpers\ExecuteHelper;
use lujie\extend\helpers\ValueHelper;
use lujie\sales\channel\channels\otto\OttoSalesChannel;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\SalesChannelInterface;
use lujie\sales\channel\SalesChannelManager;
use lujie\sales\channel\tasks\PullOttoCategoryTask;
use lujie\sales\channel\tasks\PullSalesChannelOrderTask;
use yii\base\InvalidArgumentException;
use yii\base\UserException;
use yii\console\Controller;
use yii\di\Instance;
use yii\helpers\VarDumper;

/**
 * Class SalesChannelController
 * @package lujie\sales\channel\controllers\console
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseSalesChannelController extends Controller
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

    #region ORDER

    /**
     * @param string $accountName
     * @param string $orderIdsStr
     * @inheritdoc
     */
    public function actionPullOrders(string $accountName, string $orderIdsStr): void
    {
        $account = $this->getAccount($accountName);
        $salesChannel = $this->getService($accountName);
        $orderIds = ValueHelper::strToArray($orderIdsStr);
        $salesChannelOrders = SalesChannelOrder::find()
            ->salesChannelAccountId($account->account_id)
            ->orderId($orderIds)
            ->all();
        $salesChannel->pullSalesOrders($salesChannelOrders);
    }

    #endregion
}
