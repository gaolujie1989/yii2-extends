<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\compressors;

use yii\base\BaseObject;

/**\
 * Class MockCompressor
 * @package lujie\extend\compressors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockCompressor extends BaseObject implements CompressorInterface
{
    /**
     * @param string $text
     * @return string
     * @inheritdoc
     */
    public function compress(string $text): string
    {
        return $text;
    }

    /**
     * @param string $text
     * @return string
     * @inheritdoc
     */
    public function unCompress(string $text): string
    {
        return $text;
    }
}
