<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use lujie\extend\constants\StatusConst;

/**
 * Class MemoryHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MemoryHelper
{
    public static $ALLOWED_MEMORY_LIMITS = [
        '128' => '128M',
        '256' => '256M',
        '512' => '512M',
        '1024' => '1G',
        '2048' => '2G',
        '3072' => '3G',
        '4096' => '4G',
    ];

    /**
     * @param string $memoryString
     * @return int
     * @inheritdoc
     */
    public static function getMemory(string $memoryString): int
    {
        $x = 0;
        $memoryString = strtoupper($memoryString);
        if (strpos($memoryString, 'K')) {
            $x = 1;
        } else if (strpos($memoryString, 'M')) {
            $x = 2;
        } else if (strpos($memoryString, 'G')) {
            $x = 3;
        }
        $memory = (int)trim(strtr($memoryString, ['K' => '', 'M' => '', 'G' => '', 'B' => '']));
        return $memory * (1024 ** $x);
    }

    /**
     * @param int|null $memory
     * @return string
     * @inheritdoc
     */
    public static function getAllowedMemoryLimit(?int $memory = null): string
    {
        $memoryUsage = $memory ?: memory_get_usage(true);
        $memoryUsageMB = round($memoryUsage / 1024 / 1024);
        foreach (static::$ALLOWED_MEMORY_LIMITS as $limitMB => $limitString) {
            if ($memoryUsageMB <= $limitMB) {
                return $limitString;
            }
        }
        return '4G';
    }

    /**
     * @param int $memory
     * @inheritdoc
     */
    public static function setMemoryLimit(string $memory): void
    {
        $memoryUsage = memory_get_usage(true);
        if (self::getMemory($memory) >= $memoryUsage) {
            ini_set('memory_limit', $memory);
        } else {
            ini_set('memory_limit', self::getAllowedMemoryLimit($memoryUsage));
        }
    }
}