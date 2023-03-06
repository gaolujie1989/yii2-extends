<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\engines;

use lujie\extend\helpers\TemplateHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class ExcelTemplateEngine
 * @package lujie\template\document\engines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelTemplateEngine extends BaseObject implements TemplateEngineInterface
{
    /**
     * @var array
     */
    public $tag = ['{', '}'];

    /**
     * @var string
     */
    public $output;

    /**
     * @param string $template
     * @param array $params
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Exception
     * @inheritdoc
     */
    public function render(string $template, array $params = []): string
    {
        $spreadsheet = IOFactory::load($template);
        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $data = $sheet->toArray();
            $forTemplates = $this->extractForTemplates($data, $params);
            foreach ($forTemplates as [$forValues, $from, $to]) {
                $forGeneratedData = [];
                foreach ($forValues as $forValue) {
                    for ($i = $from + 1; $i < $to; $i++) {
                        $row = $data[$i];
                        foreach ($row as $j => $value) {
                            if (empty($value)) {
                                continue;
                            }
                            $row[$j] = TemplateHelper::render($value, array_merge($params, $forValue), $this->tag);
                        }
                        $forGeneratedData[] = $row;
                    }
                }
                $length = $to - $from + 1;
                array_splice($data, $from, $length, $forGeneratedData);
                $forValuesCount = count($forValues);
                $sheet->removeRow($to);
                $sheet->removeRow($from);
                if ($forValuesCount > 1) {
                    $sheet->insertNewRowBefore($to + 1, ($length - 2) * ($forValuesCount - 1));
                }
            }

            foreach ($data as $i => $row) {
                foreach ($row as $j => $value) {
                    if (empty($value)) {
                        continue;
                    }
                    $row[$j] = TemplateHelper::render($value, $params, $this->tag);
                }
                $data[$i] = $row;
            }
            $sheet->fromArray($data);
        }

        $ext = pathinfo($template, PATHINFO_EXTENSION);
        $writer = IOFactory::createWriter($spreadsheet, ucfirst($ext));
        if (empty($this->output)) {
            $this->output = strtr($template, [".{$ext}" => "_generated.{$ext}"]);
        }
        $outputFile = TemplateHelper::render($this->output, $params, $this->tag);
        $writer->save($outputFile);
        return $outputFile;
    }

    /**
     * @param array $data
     * @param array $params
     * @throws \Exception
     * @inheritdoc
     */
    public function extractForTemplates(array $data, array $params): array
    {
        $forTagPrefix = $this->tag[0] . 'for:';
        $forTagSuffix = $this->tag[1];
        $forTagEnd = $this->tag[0] . 'end' . $this->tag[1];
        $isInFor = false;
        $forTemplates = [];
        foreach ($data as $i => $row) {
            foreach ($row as $j => $value) {
                if (empty($value)) {
                    continue;
                }
                if (StringHelper::startsWith($value, $forTagPrefix)
                    && StringHelper::endsWith($value, $forTagSuffix)) {
                    $forKey = substr($value, strlen($forTagPrefix), -strlen($forTagSuffix));
                    $forValues = ArrayHelper::getValue($params, $forKey);
                    $isInFor = true;
                    $forTemplate = [$forValues, $i];
                    break;
                }
                if ($isInFor && $value === $forTagEnd) {
                    $isInFor = false;
                    $forTemplate[] = $i;
                    $forTemplates[] = $forTemplate;
                    break;
                }
            }
        }
        return array_reverse($forTemplates);
    }
}
