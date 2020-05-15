<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\file\FileReaderInterface;
use Yii;
use yii\base\BaseObject;

/**
 * Class CsvReader
 * @package lujie\extend\file\readers
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
            $rows = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($rows as $row) {
                $data[] = str_getcsv($row, $this->delimiter, $this->enclosure, $this->escape);
            }
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

    /**
     * @param string $content
     * @return array
     * @inheritdoc
     */
    public function readContent(string $content): array
    {
        $data = [];
        $rows = explode("\n", $content);
        foreach ($rows as $row) {
            $data[] = str_getcsv($row, $this->delimiter, $this->enclosure, $this->escape);
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
