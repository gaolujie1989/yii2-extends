<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\constants;

/**
 * Class StatusConst
 * @package lujie\extend\constants
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StatusConst
{
    public const STATUS_ACTIVE = 10;
    public const STATUS_INACTIVE = 0;
    public const STATUS_ENABLE = 10;
    public const STATUS_DISABLE = 0;

    public const STATUS_LIST = [
        self::STATUS_INACTIVE,
        self::STATUS_ACTIVE,
    ];
}
