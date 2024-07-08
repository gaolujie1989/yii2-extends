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

    public function getParams(): array
    {
        return array_merge(['idFrom', 'idTo'], parent::getParams());
    }

    /**
     * @return Generator
     * @inheritdoc
     */
    public function execute(): Generator
    {
        $query = Address::find();
        if ($this->idFrom) {
            $query->andWhere(['>=', 'address_id', $this->idFrom]);
        }
        if ($this->idTo) {
            $query->andWhere(['<=', 'address_id', $this->idTo]);
        }
        $progress = $this->getProgress($query->count());
        foreach ($query->each() as $address) {
            $address->updateAttributes(['signature' => $address->generateSignature()]);
            yield ++$progress->done;
        }
    }
}
