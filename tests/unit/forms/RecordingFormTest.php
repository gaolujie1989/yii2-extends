<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\forms;


use lujie\data\loader\ArrayDataLoader;
use lujie\data\recording\forms\RecordingForm;
use lujie\data\recording\models\DataRecord;
use lujie\data\recording\tests\unit\fixtures\DataAccountFixture;
use lujie\data\recording\tests\unit\fixtures\DataSourceFixture;
use lujie\data\recording\tests\unit\mocks\MockDataRecorder;
use Yii;
use yii\helpers\Json;

class RecordingFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function _before()
    {
        Yii::$app->set('dataRecorderLoader', [
            'class' => ArrayDataLoader::class,
            'data' => [
                'MOCK' => [
                    'class' => MockDataRecorder::class
                ]
            ]
        ]);
    }

    protected function _after()
    {
    }

    public function _fixtures(): array
    {
        return [
            'dataAccount' => DataAccountFixture::class,
            'dataSource' => DataSourceFixture::class,
        ];
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function testGenerate(): void
    {

        $recordingForm = new RecordingForm();
        $recordingForm->dataSourceId = 3;
        $this->assertFalse($recordingForm->recording());
        $this->assertTrue($recordingForm->hasErrors('dataSourceId'));

        $recordingForm->dataSourceId = 1;
        $this->assertTrue($recordingForm->recording());
        $records = DataRecord::find()->dataAccountId(1)->dataSourceType('MOCK')->all();
        $this->assertCount(1, $records);
        $expected = [
            'data_id' => 1,
            'data_created_at' => 1234567890,
            'data_updated_at' => 1334567890,
        ];
        $dataRecord = $records[0];
        $this->assertEquals($expected, $dataRecord->getAttributes(array_keys($expected)));
        $expected =             [
            'id' => 1,
            'createdAt' => 1234567890,
            'updatedAt' => 1334567890,
            'xxx1' => 'xxx11',
            'xxx2' => 'xxx22',
            'yyy' => 'yy123',
        ];
        $data =  Json::decode($dataRecord->getRecordDataText());
        $this->assertEquals($expected, $data);
    }
}
