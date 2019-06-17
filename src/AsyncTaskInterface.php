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
    public function shouldAsync(): bool;

    /**
     * @return string
     * @inheritdoc
     */
    public function getAsyncAddress(): string;
}
