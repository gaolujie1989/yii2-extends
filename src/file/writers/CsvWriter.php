<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\writers;

use lujie\extend\file\FileWriterInterface;
use yii\base\BaseObject;

/**
 * Class CsvWriter
 * @package lujie\extend\file\writers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CsvWriter extends BaseObject implements FileWriterInterface
{
    public $keyAsHeader = true;

    public $delimiter = ',';
    public $enclosure = '"';
    public $escape = '\\';

    /**
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    public function write(string $file, array $data): void
    {
        if (file_exists($file)) {
            unlink($file);
        }
        if (($fp = fopen($file, 'wb')) === false) {
            return;
        }
        //add BOM to fix UTF-8 in Excel
        //fwrite($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        if ($this->keyAsHeader) {
            array_unshift($data, array_keys(reset($data)));
        }
        foreach ($data as $values) {
            fputcsv($fp, $values, $this->delimiter, $this->enclosure, $this->escape);
        }
        fclose($fp);
    }
}
