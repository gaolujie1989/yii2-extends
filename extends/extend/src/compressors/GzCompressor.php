<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\compressors;

use yii\base\BaseObject;

/**
 * Class GzCompressor
 *
 * gzencode/gzcompress/gzdeflate functions is the same, only default args is different
 * @package lujie\extend\compressors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class GzCompressor extends BaseObject implements CompressorInterface
{
    /**
     * it's funny. FORCE_GZIP === ZLIB_ENCODING_GZIP AND FORCE_DEFLATE === ZLIB_ENCODING_DEFLATE
     * @var int
     */
    public $encoding = ZLIB_ENCODING_RAW;

    /**
     * @var int
     */
    public $level = -1;

    /**
     * @param string $text
     * @return string
     * @inheritdoc
     */
    public function compress(string $text): string
    {
        if ($this->encoding === ZLIB_ENCODING_GZIP) {
            return gzencode($text, $this->level, $this->encoding);
        }
        if ($this->encoding === ZLIB_ENCODING_DEFLATE) {
            return gzcompress($text, $this->level, $this->encoding);
        }
        return gzdeflate($text, $this->level, $this->encoding);
    }

    /**
     * @param string $text
     * @return string
     * @inheritdoc
     */
    public function unCompress(string $text): string
    {
        if ($this->encoding === ZLIB_ENCODING_GZIP) {
            return gzdecode($text);
        }
        if ($this->encoding === ZLIB_ENCODING_DEFLATE) {
            return gzuncompress($text);
        }
        return gzinflate($text);
    }
}
