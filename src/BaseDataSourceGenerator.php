<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording;

use lujie\data\recording\models\DataAccount;
use lujie\data\recording\models\DataSource;
use lujie\extend\constants\StatusConst;
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
    public $defaultSourceStatus = StatusConst::STATUS_ACTIVE;

    /**
     * @var int
     */
    public $overTimeSeconds = 5;

    /**
     * @param DataAccount $dataAccount
     * @throws \Throwable
     * @inheritdoc
     */
    public function generateSources(DataAccount $dataAccount, array $dataSourceTypes, int $startTime, int $endTime, int $timePeriod = 0): array
    {
        return DataSource::getDb()->transaction(function () use ($dataAccount, $dataSourceTypes, $startTime, $endTime, $timePeriod) {
            $dataSources = [];
            foreach ($dataSourceTypes as $dataSourceType) {
                if ($timePeriod) {
                    for ($fromTime = $startTime; $fromTime < $endTime; $fromTime += $timePeriod) {
                        $toTime = min($fromTime + $timePeriod + $this->overTimeSeconds, $endTime);
                        $dataSources[] = $this->createSource($dataAccount, $dataSourceType, $fromTime, $toTime);
                    }
                } else {
                    $dataSources[] = $this->createSource($dataAccount, $dataSourceType, $startTime, $endTime);
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
     * @param int $timePrevious
     * @return DataSource|null
     * @inheritdoc
     */
    abstract protected function createSource(DataAccount $dataAccount, string $type, int $fromTime, int $toTime, $timePrevious = 5): DataSource;
}
