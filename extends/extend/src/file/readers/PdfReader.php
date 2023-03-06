<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\file\FileReaderInterface;
use Spatie\PdfToText\Pdf;
use yii\base\BaseObject;

/**
 * Class PdfReader
 * @package lujie\extend\file\readers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PdfReader extends BaseObject implements FileReaderInterface
{
    /**
     * @var array
     */
    public $options = ['raw'];

    /**
     * @var string
     */
    public $binPath = 'pdftotext';

    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    public function read(string $file): array
    {
        $text = Pdf::getText($file, $this->binPath, $this->options);
        return explode("\n", $text);
    }
}
