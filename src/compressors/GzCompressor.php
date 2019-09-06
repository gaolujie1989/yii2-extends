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
        if ($this->encoding === FORCE_GZIP || $this->encoding === FORCE_DEFLATE) {
            return gzencode($text, $this->level, $this->encoding);
        }
        if ($this->encoding === ZLIB_ENCODING_GZIP || $this->encoding === ZLIB_ENCODING_DEFLATE) {
            return gzcompress($text, $this->level, $this->encoding);
        }
        return gzdeflate($this->encoding, $this->level);
    }

    /**
     * @param string $text
     * @return string
     * @inheritdoc
     */
    public function unCompress(string $text): string
    {
        if ($this->encoding === FORCE_GZIP || $this->encoding === FORCE_DEFLATE) {
            return gzdecode($text);
        }
        if ($this->encoding === ZLIB_ENCODING_GZIP || $this->encoding === ZLIB_ENCODING_DEFLATE) {
            return gzuncompress($text);
        }
        return gzinflate($text);
    }
}
