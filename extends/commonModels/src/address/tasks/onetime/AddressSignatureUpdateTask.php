<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\address\tasks\onetime;

use Generator;
use lujie\common\address\models\Address;
use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\scheduling\CronTask;

/**
 * Class AddressSignatureUpdateTask
 * @package lujie\common\address\tasks\onetime
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AddressSignatureUpdateTask extends CronTask implements ProgressInterface
{
    use ProgressTrait;

    public $idFrom = 0;

    public $idTo = 0;

    public $idStep = 1000000;

    public function getParams(): array
    {
        return array_merge(['idFrom', 'idTo', 'idStep'], parent::getParams());
    }

    /**
     * @return Generator
     * @inheritdoc
     */
    public function execute(): Generator
    {
        $progress = $this->getProgress($this->idTo - $this->idFrom + 1);
        $stepDone = 0;
        for ($stepIdFrom = $this->idFrom; $stepIdFrom <= $this->idTo; $stepIdFrom += $this->idStep) {
            $stepIdTo = min($stepIdFrom + $this->idStep - 1, $this->idTo);
            $query = Address::find()
                ->andWhere(['BETWEEN', 'address_id', $stepIdFrom, $stepIdTo]);
            foreach ($query->each() as $address) {
                $address->updateAttributes(['signature' => $address->generateSignature()]);
                yield ++$progress->done;
            }
            $stepDone += $stepIdTo - $stepIdFrom + 1;
            yield $progress->done = $stepDone;
        }
    }
}
