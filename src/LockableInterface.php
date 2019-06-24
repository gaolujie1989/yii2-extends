<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;


use yii\mutex\Mutex;

interface LockableInterface
{
    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldLocked(): bool;

    /**
     * @return Mutex|null
     * @inheritdoc
     */
    public function getMutex(): ?Mutex;

    /**
     * @return string
     * @inheritdoc
     */
    public function getLockKey(): string;

    /**
     * @return int
     * @inheritdoc
     */
    public function getTimeout(): int;
}
