<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\currency\exchanging\tasks;

use lujie\currency\exchanging\CurrencyExchangeRateUpdater;
use lujie\scheduling\CronTask;
use yii\di\Instance;

/**
 * Class UpdateCurrencyExchangeRateTask
 * @package lujie\currency\exchanging\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UpdateCurrencyExchangeRateTask extends CronTask
{
    /**
     * @var CurrencyExchangeRateUpdater
     */
    public $currencyExchangeRateUpdater = 'currencyExchangeRateUpdater';

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->currencyExchangeRateUpdater = Instance::ensure($this->currencyExchangeRateUpdater, CurrencyExchangeRateUpdater::class);
        $this->currencyExchangeRateUpdater->updateRates();
        return true;
    }
}
