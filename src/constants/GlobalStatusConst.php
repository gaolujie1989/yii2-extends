<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\constants;


class GlobalStatusConst
{
    public const STATUS_NORMAL = 'NORMAL';
    public const STATUS_ARCHIVED = 'ARCHIVED';
    public const STATUS_DELETED = 'DELETED';

    public const STATUS_LIST = [
        self::STATUS_NORMAL,
        self::STATUS_ARCHIVED,
        self::STATUS_DELETED,
    ];
}
