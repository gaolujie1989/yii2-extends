<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;


interface ForkTaskInterface
{
    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldFork();
}