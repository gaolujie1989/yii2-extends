<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\extend\helpers;

use lujie\extend\file\writers\ZipWriter;

/**
 * Class ZipHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ZipHelper
{
    /**
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    public static function writeZip(string $file, array $data): void
    {
        $zipWriter = new ZipWriter();
        $zipWriter->write($file, $data);
    }
}
