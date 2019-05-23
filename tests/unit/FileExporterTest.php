<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit;


use lujie\data\exchange\DataExchanger;
use lujie\data\exchange\file\exporters\CsvExporter;
use lujie\data\exchange\file\parsers\CsvParser;
use lujie\data\exchange\FileExporter;
use lujie\data\exchange\pipelines\FilePipeline;
use lujie\data\exchange\sources\DbSource;
use lujie\data\exchange\transformers\KeyMapTransformer;

class FileExporterTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        $baseMigrationVersion = 'm000000_000000_base';
        $dbSource = new DbSource([
            'table' => '{{%migration}}',
            'condition' => ['version' => $baseMigrationVersion],
        ]);

        $exporter = new FileExporter([
            'transformer' => [
                'class' => KeyMapTransformer::class,
            ],
            'pipeline' => [
                'class' => FilePipeline::class,
                'fileExporter' => CsvExporter::class,
            ]
        ]);
        $file = __DIR__ . '/fixtures/export.csv';
        if (file_exists($file)) {
            unlink($file);
        }
        $csvParser = new CsvParser();
        $exporter->exportToFile($dbSource, $file);
        $this->assertEquals($dbSource->all(), $csvParser->parseFile($file));
    }
}
