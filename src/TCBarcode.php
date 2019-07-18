<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\barcode;

use Picqer\Barcode\BarcodeGeneratorJPG;
use Picqer\Barcode\Exceptions\BarcodeException;
use Yii;
use yii\base\BaseObject;
use yii\base\Component;
use yii\base\NotSupportedException;

/**
 * Class TCBarcode
 * @package lujie\barcode
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TCBarcode extends BaseObject implements BarcodeInterface
{
    /**
     * @var BarcodeGeneratorJPG
     */
    private $tcBarcode;

    /**
     * @var array
     */
    public $codeTypeMap = [
        self::CODE128 => BarcodeGeneratorJPG::TYPE_CODE_128,
        self::EAN13 => BarcodeGeneratorJPG::TYPE_EAN_13,
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tcBarcode = new BarcodeGeneratorJPG();
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
