<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\file\writers;

class XLSXWriter extends \XLSXWriter
{
    /**
     * @param $val
     * @return string
     * @inheritdoc
     */
    public static function xmlspecialchars($val): string
    {
        return parent::xmlspecialchars((string)$val);
    }
}
