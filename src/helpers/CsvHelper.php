<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\extend\helpers;

/**
 * Class CsvHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CsvHelper
{
    /**
     * @param $file
     * @param bool $firstLineIsHeader
     * @param int $length
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @return array
     * @inheritdoc
     */
    public static function readCsv(string $file, bool $firstLineIsHeader = true, int $length = 0,
                                   string $delimiter = ',', string $enclosure = '"', string $escape = '\\',
                                   bool $flag = true): array
    {
        $data = [];
        if ($flag) {
            $rows = file($file);
            $count = count($rows);
            $delimiters = array_fill(0, $count, $delimiter);
            $enclosures = array_fill(0, $count, $enclosure);
            $escapes = array_fill(0, $count, $escape);
            $data = array_map('str_getcsv', $rows, $delimiters, $enclosures, $escapes);
        } else {
            if (($handle = fopen($file, 'rb')) !== FALSE) {
                while (($row = fgetcsv($handle, $length, $delimiter, $enclosure, $escape)) !== FALSE) {
                    $data[] = $row;
                }
                fclose($handle);
            }
        }
        if ($firstLineIsHeader) {
            array_walk($data, static function (&$a) use ($data) {
                $a = array_combine($data[0], $a);
            });
            array_shift($data);
        }
        return $data;
    }

    /**
     * @param string $file
     * @param array $data
     * @param bool $keyAsHeader
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @inheritdoc
     */
    public static function writeCsv(string $file, array $data, bool $keyAsHeader = true,
                                    string $delimiter = ',', string $enclosure = '"', string $escape = '\\'): void
    {
        if (file_exists($file)) {
            unlink($file);
        }
        $fp = fopen($file, 'wb');
        if ($keyAsHeader) {
            array_unshift($data, array_keys($data[0]));
        }
        foreach ($data as $values) {
            fputcsv($fp, $values, $delimiter, $enclosure, $escape);
        }
        fclose($fp);
    }
}
