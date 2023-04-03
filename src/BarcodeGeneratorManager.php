<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\barcode\generating;

use Codeception\PHPUnit\ResultPrinter\HTML;
use phpseclib3\Crypt\EC\Curves\prime192v1;
use Picqer\Barcode\BarcodeGeneratorDynamicHTML;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorJPG;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorSVG;
use yii\base\BaseObject;

/**
 * Class BarcodeGenerator
 * @package lujie\barcode\generating
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BarcodeGeneratorManager extends BaseObject
{
    public const ENGINE_BCG = 'BCG';
    public const ENGINE_TC = 'TC';
    public const RETURN_TYPE_SVG = 'SVG';
    public const RETURN_TYPE_PNG = 'PNG';
    public const RETURN_TYPE_JPG = 'JPG';
    public const RETURN_TYPE_HTML = 'HTML';
    public const RETURN_TYPE_DYNAMIC_HTML = 'DYNAMIC_HTML';

    /**
     * @var string
     */
    private $engine = self::ENGINE_TC;

    /**
     * @var string
     */
    private $returnType = self::RETURN_TYPE_PNG;

    /**
     * @var array
     */
    private $options = [];

    private $tcBarcodes = [
        self::RETURN_TYPE_DYNAMIC_HTML => BarcodeGeneratorDynamicHTML::class,
        self::RETURN_TYPE_HTML => BarcodeGeneratorHTML::class,
        self::RETURN_TYPE_PNG => BarcodeGeneratorPNG::class,
        self::RETURN_TYPE_JPG => BarcodeGeneratorJPG::class,
        self::RETURN_TYPE_SVG => BarcodeGeneratorSVG::class,
    ];

    public function setEngine(string $engine): self
    {
        $this->engine = $engine;
        return $this;
    }

    public function setReturnType(string $returnType): self
    {
        $this->returnType = $returnType;
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     * @inheritdoc
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param string $codeType
     * @param string $codeText
     * @param array $options
     * @return bool
     * @throws \BarcodeBakery\Common\BCGArgumentException
     * @throws \BarcodeBakery\Common\BCGDrawException
     * @throws \Picqer\Barcode\Exceptions\BarcodeException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function generate(string $codeType, string $codeText, array $options = []): bool
    {
        if ($this->engine === self::ENGINE_TC) {
            $tcBarcodeClass = $this->tcBarcodes[$this->returnType];
            $barcode = new TCBarcodeGenerator(array_merge([
                'tcBarcode' => new $tcBarcodeClass(),
            ], $this->options));
        } else {
            $barcode = new BCGBarcodeGenerator();
        }
        return $barcode->generateBarcodeImage($codeType, $codeText);
    }
}