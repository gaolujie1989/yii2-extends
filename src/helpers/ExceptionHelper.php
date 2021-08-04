<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use Throwable;

/**
 * Class ExceptionHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExceptionHelper
{
    /**
     * @param Throwable $exception
     * @param int $maxLength
     * @return string
     * @inheritdoc
     */
    public static function getMessage(Throwable $exception, int $maxLength = 1000): string
    {
        $message = [
            $exception->getMessage(),
            '[' . get_class($exception) . ']',
            $exception->getTraceAsString()
        ];
        return mb_substr(implode("\n", $message), 0, $maxLength);
    }
}