<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\pipelines;


use lujie\data\recording\compress\GzDeflateCompressor;
use lujie\data\recording\models\DataAccount;
use lujie\data\recording\models\DataRecord;
use lujie\data\recording\models\DataRecordData;
use lujie\data\recording\models\DataSource;
use lujie\data\recording\pipelines\ActiveRecordRecordDataPipeline;
use lujie\data\recording\pipelines\DataRecordPipeline;
use lujie\data\recording\pipelines\FileRecordDataPipeline;
use lujie\data\recording\transformers\RecordTransformer;
use yii\queue\serializers\JsonSerializer;

class FileDataRecordPipelineTest extends \Codeception\Test\Unit
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
     * @return DataSource
     * @inheritdoc
     */
    protected function getSource(): DataSource
    {
        $account = new DataAccount([
            'name' => 'testAccount',
            'type' => 'testType',
            'options' => [
                'request' => ['xxx' => 'xxx']
            ],
        ]);
        $account->save(false);
        $source = new DataSource([
            'data_account_id' => $account->data_account_id,
            'name' => 'testSource',
            'type' => 'testType',
            'options' => [],
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
        $data['text'] = (new GzDeflateCompressor())->compress((new JsonSerializer())->serialize($data['record']));

        $pipeline = new FileRecordDataPipeline([
            'sourceId' => $source->data_source_id
        ]);
        $path = 'record_data/10/testType/123.bin';
        if ($pipeline->fs->has($path)) {
            $pipeline->fs->delete($path);
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
        $recoveryData = (new JsonSerializer())->unserialize((new GzDeflateCompressor())->unCompress($content));
        $this->assertEquals($data['record'], $recoveryData);
    }
}
