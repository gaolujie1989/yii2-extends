<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\forms;

use lujie\data\loader\ArrayDataLoader;
use lujie\data\recording\forms\RecordingForm;
use lujie\data\recording\models\DataRecord;
use lujie\data\recording\models\DataSource;
use lujie\data\recording\tests\unit\fixtures\DataSourceFixture;
use lujie\data\recording\tests\unit\mocks\MockDataRecorder;
use lujie\extend\constants\ExecStatusConst;
use Yii;
use yii\helpers\Json;

class RecordingFormTest extends \Codeception\Test\Unit
{


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
        Yii::$app->set('dataAccountLoader', [
            'class' => ArrayDataLoader::class,
            'data' => require __DIR__ . '/../fixtures/data/data_account.php'
        ]);
    }

    protected function _after()
    {
    }

    public function _fixtures(): array
    {
        return [
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
        $dataSource = DataSource::findOne(1);
        $this->assertEquals(ExecStatusConst::EXEC_STATUS_SUCCESS, $dataSource->last_exec_status);
        $expected = [
            'created' => 1,
            'skipped' => 0,
            'updated' => 0,
        ];
        $this->assertEquals($expected, $dataSource->last_exec_result);

        $records = DataRecord::find()
            ->dataAccountId($dataSource->data_account_id)
            ->dataSourceType($dataSource->type)
            ->all();
        $this->assertCount(1, $records);
        $expected = [
            'data_id' => 1,
            'data_created_at' => 1234567890,
            'data_updated_at' => 1334567890,
        ];
        $dataRecord = $records[0];
        $this->assertEquals($expected, $dataRecord->getAttributes(array_keys($expected)));
        $expected = [
            'id' => 1,
            'createdAt' => 1234567890,
            'updatedAt' => 1334567890,
            'xxx1' => 'xxx11',
            'xxx2' => 'xxx22',
            'yyy' => 'yy123',
        ];
        $data = Json::decode($dataRecord->getRecordDataText());
        $this->assertEquals($expected, $data);
    }
}
