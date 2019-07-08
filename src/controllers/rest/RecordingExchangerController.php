<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\controllers\rest;


use lujie\data\recording\RecordingExchanger;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\rest\Controller;

/**
 * Class RecordingExchangerController
 * @package lujie\data\recording\controllers\rest
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
     * @param $sourceId
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionHandle($sourceId): void
    {
        $exchange = $this->recordingExchanger->getExchange($sourceId);
        $this->recordingExchanger->handle($exchange);
    }

    /**
     * @param $sourceId
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionExecute($sourceId): void
    {
        $exchange = $this->recordingExchanger->getExchange($sourceId);
        $this->recordingExchanger->execute($exchange);
    }
}
