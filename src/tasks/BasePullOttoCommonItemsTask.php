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
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\SalesChannelManager;
use yii\base\InvalidArgumentException;
use yii\base\UserException;
use yii\di\Instance;

/**
 * Class PullOttoBrandTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BasePullOttoCommonItemsTask extends BaseSalesChannelTask implements ProgressInterface
{
    use ProgressTrait;

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
    public $importer;

    /**
     * @return array
     * @inheritdoc
     */
    public function getParams(): array
    {
        return array_merge(['page', 'total'], parent::getParams());
    }

    /**
     * @return \Generator
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\IntegrityException
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $this->importer = Instance::ensure($this->importer, DataExchanger::class);
        $batchItems = $this->batchOttoItems();
        $progress = $this->getProgress($this->total);
        foreach ($batchItems as $items) {
            $this->importer->exchange($items);
            $affectedRowCounts = $this->importer->getAffectedRowCounts();
            $affectedMessages = [];
            foreach ($affectedRowCounts as $key => $affectedCounts) {
                $affectedMessages[$key] = TemplateHelper::render($key . '[C:{created};U:{updated};S:{skipped}]', $affectedCounts);
            }
            $progress->message = implode('', $affectedMessages);
            $progress->done += count($items);
            yield true;
        }
    }

    abstract protected function batchOttoItems(): \Generator;

    /**
     * @return OttoSalesChannel
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getOttoSalesChannel(): OttoSalesChannel
    {
        $account = $this->getAccountQuery()
            ->type(SalesChannelConst::ACCOUNT_TYPE_OTTO)
            ->one();
        if ($account === null) {
            throw new InvalidArgumentException('OTTO Account is not found');
        }
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
        $salesChannel = $this->salesChannelManager->getSalesChannel($account->account_id);
        if (!($salesChannel instanceof OttoSalesChannel)) {
            throw new UserException('SalesChannel is not OTTO');
        }
        return $salesChannel;
    }
}
