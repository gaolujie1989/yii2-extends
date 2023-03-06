<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\httpclient;

use lujie\extend\compressors\GzCompressor;

/**
 * Class Response
 * @package lujie\extend\httpclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Response extends \yii\httpclient\Response
{
    /**
     * @inheritdoc
     */
    public function decodeContent(): void
    {
        $headers = $this->getHeaders();
        if (!$headers->has('content-encoding')) {
            return;
        }
        $encoding = $headers->get('content-encoding');
        $encodings = [
            'gzip' => ZLIB_ENCODING_GZIP,
            'deflate' => ZLIB_ENCODING_RAW,
        ];
        if (empty($encodings[$encoding])) {
            return;
        }
        $compressor = new GzCompressor();
        $compressor->encoding = $encodings[$encoding];

        $content = parent::getContent();
        $this->setContent($compressor->unCompress($content));
        $headers->remove('content-encoding');
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getContent(): string
    {
        $this->decodeContent();
        return parent::getContent();
    }
}