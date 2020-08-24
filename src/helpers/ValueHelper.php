<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

/**
 * Class ValueHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ValueHelper
{
    /**
     * @param mixed $value
     * @return bool
     * @inheritdoc
     */
    public static function isEmpty($value): bool
    {
        return $value === null || (is_string($value) && trim($value) === '') || (is_array($value) && count($value) === 0);
    }
}