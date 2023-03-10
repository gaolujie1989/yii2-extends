<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\file\FileReaderInterface;
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
    public $removeUtf8Bom = false;

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
                if ($this->removeUtf8Bom) {
                    $row = str_replace("\xEF\xBB\xBF", '', $row);
                }
                $data[] = str_getcsv($row, $this->delimiter, $this->enclosure, $this->escape);
            }
        } elseif (($handle = fopen($file, 'rb')) !== false) {
            while (($row = fgetcsv($handle, $this->bufferLength, $this->delimiter, $this->enclosure, $this->escape)) !== false) {
                if ($this->removeUtf8Bom) {
                    $row = str_replace("\xEF\xBB\xBF", '', $row);
                }
                $data[] = $row;
            }
            fclose($handle);
        }
        if ($this->firstLineIsHeader) {
            $this->formatData($data);
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
            $this->formatData($data);
        }
        return $data;
    }

    /**
     * @param array $data
     * @inheritdoc
     */
    protected function formatData(array &$data): void
    {
        $firstRow = (array)reset($data);
        $firstRowColumnCount = count($firstRow);
        array_walk($data, static function (&$row) use ($firstRow, $firstRowColumnCount) {
            $rowColumnCount = count($row);
            if ($firstRowColumnCount === $rowColumnCount) {
                $row = array_combine($firstRow, $row);
            } else if ($firstRowColumnCount > $rowColumnCount) {
                $keys = array_slice($firstRow, 0, $rowColumnCount);
                $row = array_combine($keys, $row);
            } else {
                $values = array_slice($row, 0, $firstRowColumnCount);
                $row = array_merge(array_combine($firstRow, $values), array_slice($row, $firstRowColumnCount));
            }
        });
        array_shift($data);
    }
}
