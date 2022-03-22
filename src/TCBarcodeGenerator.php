<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\barcode\generating;

use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\Exceptions\BarcodeException;
use Yii;
use yii\base\BaseObject;
use yii\base\NotSupportedException;

/**
 * Class TCBarcode
 * @package lujie\barcode\generating
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TCBarcodeGenerator extends BaseObject implements BarcodeGeneratorInterface
{
    /**
     * @var BarcodeGenerator
     */
    private $tcBarcode;

    /**
     * @var array
     */
    public $codeTypeMap = [
        self::CODE128 => BarcodeGenerator::TYPE_CODE_128,
        self::EAN13 => BarcodeGenerator::TYPE_EAN_13,
    ];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->tcBarcode = new BarcodeGeneratorPNG();
    }

    /**
     * @param string $codeType
     * @param string $codeText
     * @return string
     * @throws BarcodeException
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function generateBarcodeImage(string $codeType, string $codeText): string
    {
        if (empty($this->codeTypeMap[$codeType])) {
            $message = Yii::t('lujie/barcode', 'Barcode type {codeType} not supported', [
                'codeType' => $codeType
            ]);
            throw new NotSupportedException($message);
        }
        return $this->tcBarcode->getBarcode($codeText, $this->codeTypeMap[$codeType]);
    }
}
