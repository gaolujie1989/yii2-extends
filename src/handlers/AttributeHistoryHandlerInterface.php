<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ar\history\handlers;

/**
 * Interface AttributeHandlerInterface
 * @package lujie\ar\history\handlers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
interface AttributeHistoryHandlerInterface
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function extract($value);

    /**
     * @param mixed $oldValue
     * @param mixed $newValue
     * @return array|null ['added' => [...], 'deleted' => [...], 'modified' => [...]]
     */
    public function diff($oldValue, $newValue): ?array;
}