<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

use lujie\data\exchange\DataExchanger;
use lujie\data\exchange\pipelines\DbPipeline;
use lujie\data\exchange\pipelines\PipelineInterface;
use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\extend\helpers\TemplateHelper;
use lujie\sales\channel\channels\otto\OttoSalesChannel;
use lujie\sales\channel\importers\OttoCategoryImporter;
use lujie\sales\channel\models\OttoCategory;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\SalesChannelInterface;
use lujie\sales\channel\SalesChannelManager;
use lujie\scheduling\CronTask;
use yii\base\InvalidArgumentException;
use yii\base\UserException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Class PullOttoCategoryTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullOttoCategoryTask extends CronTask implements ProgressInterface
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
     * @var DataExchanger
     */
    public $importer = OttoCategoryImporter::class;

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
        $batchCategories = $salesChannel->client->batchV3ProductCategories(['page' => $this->page]);
        $progress = $this->getProgress(10000);
        foreach ($batchCategories as $categories) {
            $this->importer->exchange($categories);
            $affectedRowCounts = $this->importer->getAffectedRowCounts();
            $affectedMessages = [];
            foreach ($affectedRowCounts as $key => $affectedCounts) {
                $affectedMessages[$key] = TemplateHelper::render($key . '[C:{created};U:{updated};S:{skipped}]', $affectedCounts);
            }
            $progress->message = implode('', $affectedMessages);
            $progress->done += count($categories);
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