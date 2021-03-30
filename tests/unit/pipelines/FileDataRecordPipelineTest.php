<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\pipelines;

use lujie\data\recording\models\DataRecord;
use lujie\data\recording\models\DataSource;
use lujie\data\recording\pipelines\FileRecordDataPipeline;
use lujie\extend\compressors\GzCompressor;
use yii\queue\serializers\JsonSerializer;

class FileDataRecordPipelineTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @return DataSource
     * @inheritdoc
     */
    protected function getSource(): DataSource
    {
        $source = new DataSource([
            'data_account_id' => 1,
            'name' => 'testSource',
            'type' => 'testType',
        ]);
        $source->save(false);
        return $source;
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        $compressor = new GzCompressor();
        $serializer = new JsonSerializer();
        $source = $this->getSource();

        $data = [
            'record' => [
                'data_id' => 123,
                'data_created_at' => 123123,
                'data_updated_at' => 456456,
                'data_additional' => [
                    'x1' => 'xxx11',
                    'y' => 'yy123',
                ]
            ],
        ];
        $data['text'] = $compressor->compress($serializer->serialize($data['record']));

        $pipeline = new FileRecordDataPipeline([
            'dataSource' => $source
        ]);
        $path = 'record_data';
        if ($pipeline->fs->has($path)) {
            $pipeline->fs->deleteDir($path);
        }
        $pipeline->process([$data]);

        $record = DataRecord::find()
            ->dataAccountId($source->data_account_id)
            ->dataSourceType($source->type)
            ->dataId($data['record']['data_id'])
            ->one();
        $this->assertNotNull($record);
        $this->assertEquals($data['record'], $record->getAttributes(array_keys($data['record'])));

        $filePath = $pipeline->getFilePath($record);
        $this->assertTrue($pipeline->fs->has($filePath));
        $content = $pipeline->fs->read($filePath);
        $this->assertEquals($data['text'], $content);
        $recoveryData = $serializer->unserialize($compressor->unCompress($content));
        $this->assertEquals($data['record'], $recoveryData);
    }
}
