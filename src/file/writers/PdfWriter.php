<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\writers;

use lujie\extend\file\FileWriterInterface;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\helpers\FileHelper;
use yii2tech\html2pdf\Manager;

/**
 * Class PdfWriter
 * @package lujie\extend\file\writers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PdfWriter extends BaseObject implements FileWriterInterface
{
    /**
     * @var Manager
     */
    public $html2pdf = 'html2pdf';

    /**
     * @var array
     */
    public $options = [];

    /**
     * @param string $file
     * @param array $data
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function write(string $file, array $data): void
    {
        if (file_exists($file)) {
            unlink($file);
        }
        FileHelper::createDirectory(dirname($file));
        $this->html2pdf = Instance::ensure($this->html2pdf, Manager::class);
        $tempFile = $this->html2pdf->convert(implode('<br />', $data), $this->options);
        $tempFile->move($file);
    }
}
