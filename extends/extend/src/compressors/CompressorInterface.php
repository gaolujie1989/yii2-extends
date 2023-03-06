<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\compressors;

/**
 * Interface CompressorInterface
 * @package lujie\crawler\compress
 */
interface CompressorInterface
{
    /**
     * @param string $text
     * @return string
     * @inheritdoc
     */
    public function compress(string $text): string;

    /**
     * @param string $text
     * @return string
     * @inheritdoc
     */
    public function unCompress(string $text): string;
}
