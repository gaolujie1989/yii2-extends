<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\forms;


use lujie\data\loader\ArrayDataLoader;
use lujie\data\recording\forms\GenerateSourceForm;
use lujie\data\recording\models\DataSource;
use lujie\data\recording\tests\unit\fixtures\DataAccountFixture;
use lujie\data\recording\tests\unit\mocks\MockDataSourceGenerator;
use Yii;

class GenerateSourceFormTest extends \Codeception\Test\Unit
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
        Yii::$app->set('dataSourceGeneratorLoader', [
            'class' => ArrayDataLoader::class,
            'data' => [
                'MOCK' => [
                    'class' => MockDataSourceGenerator::class
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
        ];
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testGenerate(): void
    {
        $fromTime = strtotime('2019-01-01');
        $toTime = strtotime('2019-01-02');
        $generateSourceForm = new GenerateSourceForm();
        $generateSourceForm->dataAccountId = 1;
        $generateSourceForm->sourceTypes = ['MOCK_TYPE1', 'MOCK_TYPE2'];
        $generateSourceForm->startTime = $fromTime;
        $generateSourceForm->endTime = $toTime;

        $generateSourceForm->dataAccountId = 3;
        $this->assertFalse($generateSourceForm->generate());
        $this->assertTrue($generateSourceForm->hasErrors('dataAccountId'));
        $generateSourceForm->dataAccountId = 1;

        $this->assertTrue($generateSourceForm->generate());
        $query = DataSource::find()->dataAccountId(1)->type(['MOCK_TYPE1', 'MOCK_TYPE2']);
        $this->assertEquals(2, $query->count());
        $name = 'mock_account1:' . implode('--', [
                date('c', $fromTime - 5),
                date('c', $toTime + 5)]);
        $dataSource = $query->one();
        $this->assertEquals($name, $dataSource->name);

        $generateSourceForm->sourceTypes = ['MOCK_TYPE3', 'MOCK_TYPE4'];
        $timePeriod = ($toTime - $fromTime) / 2;
        $generateSourceForm->timePeriod = $timePeriod;
        $this->assertTrue($generateSourceForm->generate());
        $count = DataSource::find()->dataAccountId(1)->type(['MOCK_TYPE3', 'MOCK_TYPE4'])->count();
        $this->assertEquals(4, $count);
        $name1 = 'mock_account1:' . implode('--', [
                date('c', $fromTime - 5),
                date('c', $fromTime + $timePeriod + 5)]);
        $name2 = 'mock_account1:' . implode('--', [
                date('c', $fromTime + $timePeriod - 5),
                date('c', $toTime + 5)
            ]);
        $dataSources = DataSource::find()->dataAccountId(1)->type('MOCK_TYPE3')
            ->asArray()
            ->indexBy('name')
            ->all();
        $this->assertArrayHasKey($name1, $dataSources);
        $this->assertArrayHasKey($name2, $dataSources);
    }
}
