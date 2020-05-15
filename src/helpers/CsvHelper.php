<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\extend\helpers;

use lujie\extend\file\readers\CsvReader;
use lujie\extend\file\writers\CsvWriter;

/**
 * Class CsvHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CsvHelper
{
    /**
     * @param string $file
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
        $csvReader = new CsvReader();
        $csvReader->firstLineIsHeader = $firstLineIsHeader;
        $csvReader->bufferLength = $length;
        $csvReader->delimiter = $delimiter;
        $csvReader->enclosure = $enclosure;
        $csvReader->escape = $escape;
        $csvReader->flag = $flag;
        return $csvReader->read($file);
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
        $csvWriter = new CsvWriter();
        $csvWriter->keyAsHeader = $keyAsHeader;
        $csvWriter->delimiter = $delimiter;
        $csvWriter->enclosure = $enclosure;
        $csvWriter->escape = $escape;
        $csvWriter->write($file, $data);
    }

    /**
     * @param string $content
     * @param bool $firstLineIsHeader
     * @param int $length
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @param bool $flag
     * @return array
     * @inheritdoc
     */
    public static function readContent(string $content, bool $firstLineIsHeader = true, int $length = 0,
                                   string $delimiter = ',', string $enclosure = '"', string $escape = '\\',
                                   bool $flag = true): array
    {
        $csvReader = new CsvReader();
        $csvReader->firstLineIsHeader = $firstLineIsHeader;
        $csvReader->bufferLength = $length;
        $csvReader->delimiter = $delimiter;
        $csvReader->enclosure = $enclosure;
        $csvReader->escape = $escape;
        $csvReader->flag = $flag;
        return $csvReader->readContent($content);
    }
}
