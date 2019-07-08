<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording;

use Cron\CronExpression;
use lujie\data\exchange\DataExchange;
use lujie\data\exchange\Exchanger;
use lujie\data\loader\DataLoaderInterface;
use lujie\data\recording\models\DataAccountQuery;
use lujie\data\recording\models\DataSource;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;

/**
 * Class DataRecording
 * @package lujie\data\recording
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RecordingExchanger extends Exchanger
{
    /**
     * @var string
     */
    public $mutexNamePrefix = 'RecordingExchanger:';

    /**
     * @var DataLoaderInterface|RecordingExchangeLoader
     */
    public $exchangeLoader = RecordingExchangeLoader::class;

    /**
     * @param $key
     * @return DataExchange
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getExchange($key): DataExchange
    {
        /** @var DataExchange $dataExchange */
        $dataExchange = $this->exchangeLoader->get($key);
        if ($dataExchange === null) {
            throw new InvalidArgumentException("Exchange {$key} not found.");
        }
        $dataExchange->shouldQueued = true;
        $dataExchange->ttr = 3600;
        $dataExchange->shouldLocked = true;
        return $dataExchange;
    }

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function run(): void
    {
        $closure = static function (DataAccountQuery $query) {
            $query->alias('a')->active();
        };
        $query = DataSource::find()->alias('s')->innerJoinWith(['dataAccount' => $closure])->active();
        /** @var DataSource[] $each */
        $each = $query->each();
        foreach ($each as $dataSource) {
            if ($this->isDue($dataSource)) {
                $dataExchange = $this->getExchange($dataSource->data_source_id);
                $this->handle($dataExchange);
            }
        }
    }

    /**
     * @param DataSource $dataSource
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    protected function isDue(DataSource $dataSource): bool
    {
        $dateTime = new \DateTime();
        if ($dataSource->getTimezone()) {
            $dateTime->setTimezone(new \DateTimeZone($dataSource->getTimezone()));
        }
        return CronExpression::factory($dataSource->getCronExpression())->isDue($dateTime);
    }
}
