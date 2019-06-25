<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging\compress;

use yii\base\BaseObject;

/**
 * Class Compressor
 * @package lujie\data\staging\compress
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class GzDeflateCompressor extends BaseObject implements CompressorInterface
{
    public function compress(string $text): string
    {
        return gzdeflate($text);
    }

    public function unCompress(string $text): string
    {
        return gzinflate($text);
    }
}
