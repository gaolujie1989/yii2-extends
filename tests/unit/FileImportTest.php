<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit;


use lujie\data\exchange\DataExchanger;
use lujie\extend\file\writers\CsvWriter;
use lujie\data\exchange\FileImporter;
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
        $csvExporter = new CsvWriter();
        FileHelper::createDirectory($dir);
        $csvExporter->write($filePath, $data);

        $importer = new FileImporter([
            'pipeline' => [
                'class' => DbPipeline::class,
                'table' => '{{%migration}}',
            ]
        ]);
        $importer->import($filePath);
        $this->assertEquals($data, $dbSource->all());
    }
}
