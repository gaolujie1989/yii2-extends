<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\jobs;

use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\SalesChannelManager;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\queue\JobInterface;

/**
 * Class BaseSalesChannelOrderJob
 * @package lujie\sales\channel\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseSalesChannelOrderJob extends BaseObject implements JobInterface
{
    /**
     * @var SalesChannelManager
     */
    public $salesChannelManager = 'salesChannelManager';

    /**
     * @var int
     */
    public $salesChannelOrderId;

    /**
     * @return SalesChannelOrder
     * @inheritdoc
     */
    protected function getSalesChannelOrder(): SalesChannelOrder
    {
        /** @var ?SalesChannelOrder $salesChannelOrder */
        $salesChannelOrder = SalesChannelOrder::findOne($this->salesChannelOrderId);
        if ($salesChannelOrder === null) {
            throw new InvalidArgumentException("Invalid salesChannelOrderId {$this->salesChannelOrderId}");
        }
        return $salesChannelOrder;
    }
}
