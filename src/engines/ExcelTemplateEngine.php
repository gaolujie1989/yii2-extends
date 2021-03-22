<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\engines;

use lujie\extend\helpers\TemplateHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\base\BaseObject;

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
     * @inheritdoc
     */
    public function render(string $template, array $params = []): string
    {
        $spreadsheet = IOFactory::load($template);
        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $data = $sheet->toArray();
            foreach ($data as $i => $row) {
                foreach ($row as $j => $value) {
                    if ($value) {
                        $row[$j] = TemplateHelper::render($value, $params, $this->tag);
                    }
                }
                $data[$i] = $row;
            }
            $sheet->fromArray($data);
        }

        $outputFile = TemplateHelper::render($this->output, $params, $this->tag);
        $type = ucfirst(pathinfo($outputFile, PATHINFO_EXTENSION));
        $writer = IOFactory::createWriter($spreadsheet, $type);
        $writer->save($outputFile);
        return $outputFile;
    }
}