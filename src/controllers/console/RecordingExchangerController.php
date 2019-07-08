<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\controllers\console;


use lujie\data\recording\RecordingExchanger;
use Yii;
use yii\console\Controller;
use yii\di\Instance;

/**
 * Class RecordingExchangerController
 * @package lujie\data\recording\controllers\console
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RecordingExchangerController extends Controller
{
    /**
     * @var RecordingExchanger
     */
    public $recordingExchanger = 'recordingExchanger';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->recordingExchanger = Instance::ensure($this->recordingExchanger, RecordingExchanger::class);
    }

    /**
     * @inheritdoc
     */
    public function actionRunAlways(): void
    {
        while (true) {
            $sec = (int)date('s');
            if ($sec < 5) {
                try {
                    $this->recordingExchanger->run();

                    $memoryUsage = round(memory_get_peak_usage(true) / 1024 / 1024, 2); //MB
                    $this->stdout("Memory usage {$memoryUsage} MB\n");
                    if ($memoryUsage > 100) {
                        break;
                    }
                } catch (\Throwable $e) {
                    Yii::error($e, __METHOD__);
                } finally {
                    $sec = (int)date('s');
                    sleep(60 - $sec);
                }
            } else {
                sleep(60 - $sec);
            }
        }
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function actionRun(): void
    {
        $this->recordingExchanger->run();
    }

    /**
     * @param string $sourceId
     * @throws \Throwable
     * @inheritdoc
     */
    public function actionHandle(int $sourceId): void
    {
        $exchange = $this->recordingExchanger->getExchange($sourceId);
        $this->recordingExchanger->handle($exchange);
    }

    /**
     * @param int $sourceId
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionExecute(int $sourceId): void
    {
        $exchange = $this->recordingExchanger->getExchange($sourceId);
        $this->recordingExchanger->execute($exchange);
    }
}
