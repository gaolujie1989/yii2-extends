<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\tests\unit;

use lujie\template\document\engines\ExcelTemplateEngine;

/**
 * Class ExcelTemplateEngineTest
 * @package lujie\template\document\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelTemplateEngineTest extends \Codeception\Test\Unit
{
    /**
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $templateEngine = new ExcelTemplateEngine([
            'output' => dirname(__DIR__) . '/_output/template_generated.xlsx'
        ]);
        $template = __DIR__ . '/fixtures/template.xlsx';
        $templateEngine->render($template, [
            'subtitle' => 'TEST',
            'valueA' => 'xxxA',
            'valueB' => 'xxxB',
            'amount' => 1234.4,
            'items' => [
                [
                    'itemNo' => 'Item-AAA',
                    'qty' => 12,
                    'price' => 12.3,
                ],
                [
                    'itemNo' => 'Item-BBB',
                    'qty' => 21,
                    'price' => 23.1,
                ],
                [
                    'itemNo' => 'Item-CC',
                    'qty' => 21,
                    'price' => 23.1,
                ],
                [
                    'itemNo' => 'Item-DD',
                    'qty' => 21,
                    'price' => 23.1,
                ]
            ]
        ]);
    }
}
