<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

use lujie\data\exchange\DataExchanger;
use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\extend\helpers\TemplateHelper;
use lujie\sales\channel\channels\otto\OttoSalesChannel;
use lujie\sales\channel\importers\OttoBrandImporter;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\SalesChannelInterface;
use lujie\sales\channel\SalesChannelManager;
use lujie\scheduling\CronTask;
use yii\base\InvalidArgumentException;
use yii\base\UserException;
use yii\di\Instance;

/**
 * Class PullOttoBrandTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullOttoBrandTask extends CronTask implements ProgressInterface
{
    use ProgressTrait;

    /**
     * @var SalesChannelManager
     */
    public $salesChannelManager = 'salesChannelManager';

    /**
     * @var string
     */
    public $accountName;

    /**
     * @var int
     */
    public $page = 0;

    /**
     * @var int
     */
    public $total = 2000;

    /**
     * @var DataExchanger
     */
    public $importer = OttoBrandImporter::class;

    /**
     * @return \Generator
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\IntegrityException
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $salesChannel = $this->getService($this->accountName);
        if (!($salesChannel instanceof OttoSalesChannel)) {
            throw new UserException('SalesChannel is not OTTO');
        }
        $this->importer = Instance::ensure($this->importer, DataExchanger::class);
        $batchBrands = $salesChannel->client->batchV3ProductBrands(['page' => $this->page]);
        $progress = $this->getProgress($this->total);
        foreach ($batchBrands as $brands) {
            $this->importer->exchange($brands);
            $affectedRowCounts = $this->importer->getAffectedRowCounts();
            $affectedMessages = [];
            foreach ($affectedRowCounts as $key => $affectedCounts) {
                $affectedMessages[$key] = TemplateHelper::render($key . '[C:{created};U:{updated};S:{skipped}]', $affectedCounts);
            }
            $progress->message = implode('', $affectedMessages);
            $progress->done += count($brands);
            yield true;
        }
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
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getService(string $accountName): SalesChannelInterface
    {
        $account = $this->getAccount($accountName);
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
        return $this->salesChannelManager->salesChannelLoader->get($account->account_id);
    }
}