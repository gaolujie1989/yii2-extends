<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sharding\db;

use yii\helpers\ArrayHelper;

trait ShardingChosenTrait
{
    /**
     * @var array
     */
    private $shardingValues = [];

    /**
     * @var bool
     */
    private $shardingChosen = false;

    /**
     * @param array $condition
     * @param bool $append
     * @inheritdoc
     */
    protected function setShardingValues(array $condition, bool $append = true): void
    {
        if ($this->shardingChosen) {
            return;
        }
        if (!$append) {
            $this->shardingValues = [];
        }
        if (isset($condition[0], $condition[1], $condition[2])) {
            $this->shardingValues[$condition[1]] = $condition[2];
        }
        if (ArrayHelper::isAssociative($condition)) {
            $this->shardingValues = array_merge($this->shardingValues, $condition);
        }
    }

    /**
     * 手动设置sharding，设置之后，自动setShardingValues会跳过
     * @param array $values
     * @return $this
     * @inheritdoc
     */
    public function chooseSharding(array $values): self
    {
        $this->shardingChosen = false;
        $this->setShardingValues($values, false);
        $this->shardingChosen = true;
        return $this;
    }
}