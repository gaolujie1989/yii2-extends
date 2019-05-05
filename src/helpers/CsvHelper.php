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
    public static function readCsv($file, $firstLineIsHeader = true, $length = 0, $delimiter = ',', $enclosure = '"', $escape = '\\', $flag = true)
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
            if (($handle = fopen($file, "r")) !== FALSE) {
                while (($row = fgetcsv($handle, $length, $delimiter, $enclosure, $escape)) !== FALSE) {
                    $data[] = $row;
                }
                fclose($handle);
            }
        }
        if ($firstLineIsHeader) {
            array_walk($data, function (&$a) use ($data) {
                $a = array_combine($data[0], $a);
            });
            array_shift($data);
        }
        return $data;
    }

    /**
     * @param $file
     * @param $data
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @inheritdoc
     */
    public static function writeCsv($file, $data, $keyAsHeader = true, $delimiter = ",", $enclosure = '"', $escape = "\\")
    {
        if (file_exists($file)) {
            unlink($file);
        }
        $fp = fopen($file, 'w');
        if ($keyAsHeader) {
            array_unshift($data, array_keys($data[0]));
        }
        foreach ($data as $values) {
            fputcsv($fp, $values, $delimiter, $enclosure, $escape);
        }
        fclose($fp);
    }
}
