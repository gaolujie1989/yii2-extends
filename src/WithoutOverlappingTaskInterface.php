<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;


use yii\mutex\Mutex;

interface WithoutOverlappingTaskInterface
{

    /**
     * @return bool
     * @inheritdoc
     */
    public function isWithoutOverlapping();

    /**
     * @return int
     * @inheritdoc
     */
    public function getExpiresAt();

    /**
     * @return Mutex
     * @inheritdoc
     */
    public function getMutex();
}