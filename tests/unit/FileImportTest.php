<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit;


use lujie\data\exchange\DataExchange;
use lujie\data\exchange\file\exporters\CsvExporter;
use lujie\data\exchange\FileImport;
use lujie\data\exchange\pipelines\DbPipeline;
use lujie\data\exchange\sources\DbSource;
use yii\helpers\FileHelper;

class FileImportTest extends \Codeception\Test\Unit
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

        $file = 'import.csv';
        $dir = '/tmp/imports/';
        $filePath = $dir . $file;
        $csvExporter = new CsvExporter();
        FileHelper::createDirectory($dir);
        $csvExporter->exportToFile($filePath, $data);

        $importer = new FileImport([
            'pipeline' => [
                'class' => DbPipeline::class,
                'table' => '{{%migration}}',
            ]
        ]);
        $importer->import($file);
        $this->assertEquals($data, $dbSource->all());
    }
}
