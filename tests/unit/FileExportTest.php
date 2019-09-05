<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit;


use lujie\data\exchange\DataExchanger;
use lujie\data\exchange\file\writers\CsvWriter;
use lujie\data\exchange\file\readers\CsvReader;
use lujie\data\exchange\FileExporter;
use lujie\data\exchange\pipelines\FilePipeline;
use lujie\data\exchange\sources\DbSource;
use lujie\data\exchange\transformers\KeyMapTransformer;

class FileExportTest extends \Codeception\Test\Unit
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
            'source' => $dbSource,
            'transformer' => [
                'class' => KeyMapTransformer::class,
            ],
            'pipeline' => [
                'class' => FilePipeline::class,
                'fileWriter' => CsvWriter::class,
            ]
        ]);
        $file = 'export.csv';
        $csvParser = new CsvReader();
        $this->assertTrue($exporter->export($file));
        $filePath = $exporter->pipeline->getFilePath();
        $this->assertFileExists($filePath);
        $this->assertEquals($dbSource->all(), $csvParser->read($filePath));
    }
}
