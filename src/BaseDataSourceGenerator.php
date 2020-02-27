<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording;

use lujie\data\recording\models\DataAccount;
use lujie\data\recording\models\DataSource;
use lujie\extend\constants\StatusConst;
use Yii;
use yii\base\BaseObject;

/**
 * Class PlentyMarketsSourceGenerator
 * @package lujie\data\recording\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseDataSourceGenerator extends BaseObject
{
    /**
     * @var int
     */
    public $sourceStatus = StatusConst::STATUS_ACTIVE;

    /**
     * @var int
     */
    public $previousTimeSeconds = 5;

    /**
     * @var int
     */
    public $overTimeSeconds = 5;

    /**
     * @param DataAccount $dataAccount
     * @throws \Throwable
     * @inheritdoc
     */
    public function generateSources(DataAccount $dataAccount, array $dataSourceTypes, int $startTime, int $endTime, ?int $timePeriod = 0): array
    {
        return DataSource::getDb()->transaction(function () use ($dataAccount, $dataSourceTypes, $startTime, $endTime, $timePeriod) {
            $dataSources = [];
            $message = "GenerateSources {$dataAccount->name} [" . implode(',', $dataSourceTypes) . ']';
            Yii::info($message, __METHOD__);
            foreach ($dataSourceTypes as $dataSourceType) {
                if ($timePeriod) {
                    for ($fromTime = $startTime; $fromTime < $endTime; $fromTime += $timePeriod) {
                        $toTime = min($fromTime + $timePeriod, $endTime);
                        $dataSources[] = $this->createSource(
                            $dataAccount,
                            $dataSourceType,
                            $fromTime - $this->previousTimeSeconds,
                            $toTime + $this->overTimeSeconds
                        );
                    }
                } else {
                    $dataSources[] = $this->createSource(
                        $dataAccount,
                        $dataSourceType,
                        $startTime - $this->previousTimeSeconds,
                        $endTime + $this->overTimeSeconds
                    );
                }
            }
            return $dataSources;
        });
    }

    /**
     * @param DataAccount $dataAccount
     * @param string $type
     * @param int $fromTime
     * @param int $toTime
     * @return DataSource
     * @inheritdoc
     */
    abstract protected function createSource(DataAccount $dataAccount, string $type, int $fromTime, int $toTime): DataSource;

    /**
     * @param DataAccount $dataAccount
     * @param string $type
     * @param int $fromTime
     * @param int $toTime
     * @param string $timeField
     * @return DataSource
     * @inheritdoc
     */
    protected function createRecordSource(DataAccount $dataAccount, string $type,
                                          int $fromTime, int $toTime, string $timeField = 'data_updated_at'): DataSource
    {
        $exportSource = new DataSource();
        $exportSource->data_account_id = $dataAccount->data_account_id;
        $exportSource->type = $type;
        $exportSource->condition = ['BETWEEN', $timeField, $fromTime, $toTime];
        $exportSource->name = implode('--', [date('c', $fromTime), date('c', $toTime)]);
        $exportSource->status = $this->sourceStatus;
        $message = "CreateRecordSource {$dataAccount->name} {$type}, condition: [" . implode(',', $exportSource->condition) . ']';
        Yii::info($message, __METHOD__);
        $exportSource->save(false);
        return $exportSource;
    }
}
