<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\barcode\generating;

use BarcodeBakery\Barcode\BCGcode128;
use BarcodeBakery\Barcode\BCGean13;
use BarcodeBakery\Common\BCGArgumentException;
use BarcodeBakery\Common\BCGColor;
use BarcodeBakery\Common\BCGDrawException;
use BarcodeBakery\Common\BCGDrawing;
use BarcodeBakery\Common\BCGFontFile;
use BarcodeBakery\Common\BCGFontPhp;
use Exception;
use lujie\extend\helpers\TemplateHelper;
use Yii;
use yii\base\BaseObject;
use yii\base\NotSupportedException;

/**
 * Class BCGBarcode
 * @package lujie\barcode\generating
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BCGBarcodeGenerator extends BaseObject implements BarcodeGeneratorInterface
{
    /**
     * @var array
     */
    public $codeTypeClasses = [
        self::CODE128 => BCGcode128::class,
        self::EAN13 => BCGean13::class,
    ];

    public $options = [
        'fileType' => BCGDrawing::IMG_FORMAT_PNG,
        'dpi' => 300,  //72 ~ 300
        'scale' => 4,
        'thickness' => 30, //9 ~ 90
        'rotation' => 0,
        'fontFamily' => 'Arial.ttf',
        'fontSize' => 30,
    ];

    public $tmpPath = '/tmp/';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->tmpPath = rtrim(Yii::getAlias($this->tmpPath), '/') . '/';
    }

    /**
     * @param string $codeType
     * @param string $codeText
     * @return string
     * @throws BCGArgumentException
     * @throws BCGDrawException
     * @throws NotSupportedException
     * @throws Exception
     * @inheritdoc
     */
    public function generateBarcodeImage(string $codeType, string $codeText): string
    {
        if (empty($this->codeTypeClasses[$codeType])) {
            $message = Yii::t('lujie/barcode', 'Barcode type {codeType} not supported', [
                'codeType' => $codeType
            ]);
            throw new NotSupportedException($message);
        }

        $colorBlack = new BCGColor(0, 0, 0);
        $colorWhite = new BCGColor(255, 255, 255);

        /** @var BCGcode128 $barcode */
        $barcode = new $this->codeTypeClasses[$codeType]();
        $barcode->setScale($this->options['scale']);
        $barcode->setThickness($this->options['thickness']);
        $fontPath = __DIR__ . '/fonts/' . $this->options['fontFamily'];
        if ($this->options['fontFamily'] && file_exists($fontPath)) {
            $barcode->setFont(new BCGFontFile($fontPath, $this->options['fontSize']));
        } else {
            $barcode->setFont(new BCGFontPhp($this->options['fontSize']));
        }
        $barcode->setLabel($codeText);
        $barcode->setBackgroundColor($colorWhite);
        $barcode->setForegroundColor($colorBlack);
        $barcode->parse($codeText);

        $tmpFileName = $this->tmpPath . TemplateHelper::generateRandomFileName();
        $drawing = new BCGDrawing($tmpFileName, $colorWhite);
        $drawing->setBarcode($barcode);
        $drawing->setRotationAngle($this->options['rotation']);
        $drawing->setDPI($this->options['dpi']);
        $drawing->draw();
        $drawing->finish($this->options['fileType']);
        $contents = file_get_contents($tmpFileName);
        unlink($tmpFileName);
        return $contents;
    }
}
