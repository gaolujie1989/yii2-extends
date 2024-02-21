<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\barcode\generating;

/**
 * Class Barcode
 * @package lujie\barcode\generating
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
interface BarcodeGeneratorInterface
{
    public const CODE39 = 'CODE39';
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
