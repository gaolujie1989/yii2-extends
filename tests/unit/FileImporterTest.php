<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit;


use lujie\data\exchange\Exchanger;
use lujie\data\exchange\file\exporters\CsvExporter;
use lujie\data\exchange\FileImporter;
use lujie\data\exchange\pipelines\DbPipeline;
use lujie\data\exchange\sources\DbSource;

class FileImporterTest extends \Codeception\Test\Unit
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
        $testMigrationVersion = 'm000000_000000_base_test';
        $dbSource = new DbSource([
            'table' => '{{%migration}}',
            'condition' => ['version' => $testMigrationVersion],
        ]);

        $data = [
            ['version' => $testMigrationVersion, 'apply_time' => 222],
        ];
        $file = __DIR__ . '/fixtures/import.csv';
        if (file_exists($file)) {
            unlink($file);
        }
        $csvExporter = new CsvExporter();
        $csvExporter->exportToFile($file, $data);

        $importer = new FileImporter([
            'pipeline' => [
                'class' => DbPipeline::class,
                'table' => '{{%migration}}',
            ]
        ]);
        $importer->importFromFile($file);
        $this->assertEquals($data, $dbSource->all());
    }
}
