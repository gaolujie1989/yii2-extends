<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;


interface AsyncTaskInterface
{
    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldAsync();

    /**
     * @return string
     * @inheritdoc
     */
    public function getAsyncAddress();
}