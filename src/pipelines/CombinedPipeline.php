<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

use yii\base\BaseObject;
use yii\db\Connection;
use yii\di\Instance;

/**
 * Class CombinedPipeline
 * @package lujie\data\exchange\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CombinedPipeline extends BaseObject implements DbPipelineInterface
{
    /**
     * @var PipelineInterface[]
     */
    public $pipelines = [];

    /**
     * @var Connection
     */
    public $transactionDb = 'db';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->pipelines as $key => $pipeline) {
            $this->pipelines[$key] = Instance::ensure($pipeline, PipelineInterface::class);
        }
        if ($this->transactionDb) {
            $this->transactionDb = Instance::ensure($this->transactionDb, Connection::class);
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function process(array $data): bool
    {
        $callable = function () use ($data) {
            foreach ($this->pipelines as $key => $pipeline) {
                if (isset($data[$key])) {
                    $pipeline->process($data[$key]);
                }
            }
            return true;
        };

        if ($this->transactionDb) {
            return $this->transactionDb->transaction($callable);
        }
        return $callable();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getAffectedRowCounts(): array
    {
        $affectedRowCounts = [];
        foreach ($this->pipelines as $key => $pipeline) {
            if ($pipeline instanceof DbPipelineInterface) {
                $affectedRowCounts[$key] = $pipeline->getAffectedRowCounts();
            }
        }
        return $affectedRowCounts;
    }
}
