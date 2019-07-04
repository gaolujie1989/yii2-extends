<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\compress;

/**
 * Interface Compressor
 * @package lujie\data\recording\compress
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
