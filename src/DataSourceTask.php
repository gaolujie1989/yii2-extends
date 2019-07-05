<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording;

use lujie\scheduling\CronTask;
use yii\di\Instance;

/**
 * Class DataSourceTask
 * @package lujie\data\recording
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataSourceTask extends CronTask
{
    /**
     * @var RecordingExchanger
     */
    public $recordingExchanger = 'recordingExchanger';

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->recordingExchanger = Instance::ensure($this->recordingExchanger, RecordingExchanger::class);
        $dataExchange = $this->recordingExchanger->getExchange($this->getId());
        $this->recordingExchanger->execute($dataExchange);
        return true;
    }
}
