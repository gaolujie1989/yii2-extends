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
     * @var bool
     */
    public $autoDecode = true;

    /**
     * @var ?string
     */
    public $rawContent;

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

        $this->rawContent = parent::getContent();
        $this->setContent($compressor->unCompress($this->rawContent));
        $headers->remove('content-encoding');
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getContent(): string
    {
        if ($this->autoDecode) {
            $this->decodeContent();
        }
        return parent::getContent();
    }
}
