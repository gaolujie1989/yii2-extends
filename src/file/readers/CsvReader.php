<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\file\FileReaderInterface;
use yii\base\BaseObject;

/**
 * Class CsvParser
 * @package lujie\data\exchange\parsers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CsvReader extends BaseObject implements FileReaderInterface
{
    public $firstLineIsHeader = true;

    public $delimiter = ',';
    public $enclosure = '"';
    public $escape = '\\';
    public $flag = true;
    public $bufferLength = 0;

    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    public function read(string $file): array
    {
        $data = [];
        if ($this->flag) {
            $rows = file($file);
            $count = count($rows);
            $delimiters = array_fill(0, $count, $this->delimiter);
            $enclosures = array_fill(0, $count, $this->enclosure);
            $escapes = array_fill(0, $count, $this->escape);
            $data = array_map('str_getcsv', $rows, $delimiters, $enclosures, $escapes);
        } else if (($handle = fopen($file, 'rb')) !== FALSE) {
            while (($row = fgetcsv($handle, $this->bufferLength, $this->delimiter, $this->enclosure, $this->escape)) !== FALSE) {
                $data[] = $row;
            }
            fclose($handle);
        }
        if ($this->firstLineIsHeader) {
            array_walk($data, static function (&$a) use ($data) {
                $a = array_combine($data[0], $a);
            });
            array_shift($data);
        }
        return $data;
    }
}
