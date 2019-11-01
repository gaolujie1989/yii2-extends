<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\compressors;


use lujie\extend\compressors\GzCompressor;

class GzCompressorTest extends \Codeception\Test\Unit
{
    /**
     * @var \lujie\extend\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @inheritdoc
     */
    public function testMe(): void
    {
        $text = '万圣节又叫诸圣节，在每年的11月1日，是西方的传统节日；而万圣节前夜的10月31日是这个节日最热闹的时刻';
        $encodings = [
//            FORCE_GZIP,
//            FORCE_DEFLATE,
//            ZLIB_ENCODING_GZIP,
//            ZLIB_ENCODING_DEFLATE,
            ZLIB_ENCODING_RAW
        ];
        $gzCompressor = new GzCompressor();
        foreach ($encodings as $encoding) {
            $gzCompressor->encoding = $encoding;
            $compressedText = $gzCompressor->compress($text);
            $unCompressedText = $gzCompressor->unCompress($compressedText);
            $this->assertEquals($text, $unCompressedText);
        }
    }
}
