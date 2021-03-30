<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\barcode\generating;

use yii\base\BaseObject;

/**
 * Class TecITBarcode
 * @package ccship\common
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TecITBarcodeGenerator extends BaseObject implements BarcodeGeneratorInterface
{
    public $barcodeUrl = 'https://barcode.tec-it.com/barcode.ashx?';

    public $defaultRequestData = [
        'multiplebarcodes' => false,
        'translate-esc' => false,
        'unit' => 'Fit',
        'dpi' => 96,
        'imagetype' => 'Png',
        'rotation' => 0,
        'color' => '#000000',
        'bgcolor' => '#ffffff',
        'qunit' => 'Mm',
        'quiet' => 0,
        'base64' => false,
    ];

    /**
     * @param string $codeType
     * @param string $codeText
     * @return string
     * @inheritdoc
     */
    public function generateBarcodeImage(string $codeType, string $codeText): string
    {
        $data = array_merge($this->defaultRequestData, [
            'data' => $codeText,
            'code' => $codeType,
        ]);
        return file_get_contents($this->barcodeUrl . http_build_query($data));
    }
}
