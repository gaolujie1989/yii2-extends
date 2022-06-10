<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

interface ExecutableInterface
{
    /**
     * @return string|int
     * @inheritdoc
     */
    public function getId();

    /**
     * @return string|int
     * @inheritdoc
     */
    public function getExecUid();

    /**
     * @return string
     * @inheritdoc
     */
    public function getMemoryLimit(): ?string;

    /**
     * @return mixed
     * @inheritdoc
     */
    public function execute();
}
