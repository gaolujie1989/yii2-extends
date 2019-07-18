<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\barcode;

/**
 * Class Barcode
 * @package lujie\barcode
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
interface BarcodeInterface
{
    public const CODE128 = 'CODE128';
    public const EAN13 = 'EAN13';

    /**
     * @param string $codeType
     * @param string $codeText
     * @return string return image data
     * @inheritdoc
     */
    public function generateBarcodeImage(string $codeType, string $codeText): string;
}
