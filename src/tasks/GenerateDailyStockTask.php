<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;


use lujie\fulfillment\DailyStockGenerator;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class GenerateDailyStockTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class GenerateDailyStockTask extends CronTask
{
    /**
     * @var string
     */
    public $stockDateFrom = '-2 days';

    /**
     * @var string
     */
    public $stockDateTo = '-1 days';

    /**
     * @var DailyStockGenerator
     */
    public $dailyStockGenerator = DailyStockGenerator::class;

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->dailyStockGenerator = Instance::ensure($this->dailyStockGenerator, DailyStockGenerator::class);
        $this->dailyStockGenerator->generateDailyStockMovements($this->stockDateFrom, $this->stockDateTo);
        $this->dailyStockGenerator->generateDailyStocks($this->stockDateFrom, $this->stockDateTo);
    }
}