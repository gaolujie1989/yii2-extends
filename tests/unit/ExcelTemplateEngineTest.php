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
            'output' => dirname(__DIR__) . '/_output/template.xlsx'
        ]);
        $template = __DIR__ . '/fixtures/template.xlsx';
        $templateEngine->render($template, [
            'subtitle' => 'TEST',
            'valueA' => 'xxxA',
            'valueB' => 'xxxB',
            'itemNo' => 'Item-XXA',
            'qty' => 12,
            'price' => 12.3,
        ]);
    }
}
