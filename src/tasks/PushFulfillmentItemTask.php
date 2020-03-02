<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;

use lujie\fulfillment\FulfillmentManager;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class PushPendingFulfillmentOrderTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PushFulfillmentItemTask extends CronTask
{
    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $this->fulfillmentManager->pushFulfillmentItems();
        return true;
    }
}
