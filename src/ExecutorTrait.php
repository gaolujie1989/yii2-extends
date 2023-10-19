<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\executing;

use yii\di\Instance;

/**
 * Trait ExecutorTrait
 *
 * @property string $executor
 *
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait ExecutorTrait
{
    /**
     * @var ?Executor
     */
    private $_executor;

    /**
     * @return Executor
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getExecutor(): Executor
    {
        if ($this->_executor === null) {
            $this->_executor = Instance::ensure($this->executor ?? 'scheduler');
        }
        return $this->_executor;
    }

    /**
     * @param ExecutableInterface $executable
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function handle(ExecutableInterface $executable): bool
    {
        return $this->getExecutor()->handle($executable);
    }
}
