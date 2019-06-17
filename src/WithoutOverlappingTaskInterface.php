<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;

use yii\mutex\Mutex;

/**
 * Interface WithoutOverlappingTaskInterface
 * @package lujie\scheduling
 */
interface WithoutOverlappingTaskInterface
{

    /**
     * @return bool
     * @inheritdoc
     */
    public function isWithoutOverlapping(): bool;

    /**
     * @return int
     * @inheritdoc
     */
    public function getExpiresAt(): int;

    /**
     * @return Mutex|null
     * @inheritdoc
     */
    public function getMutex(): ?Mutex;
}
