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
        $now = time();
        $generateSourceForm = new GenerateSourceForm();
        $generateSourceForm->dataAccountId = 1;
        $generateSourceForm->sourceTypes = ['MOCK_TYPE1', 'MOCK_TYPE2'];
        $generateSourceForm->startTime = $now - 10800;
        $generateSourceForm->endTime = $now;

        $generateSourceForm->dataAccountId = 3;
        $this->assertFalse($generateSourceForm->generate());
        $this->assertTrue($generateSourceForm->hasErrors('dataAccountId'));
        $generateSourceForm->dataAccountId = 1;

        $this->assertTrue($generateSourceForm->generate());
        $count = DataSource::find()->dataAccountId(1)->type(['MOCK_TYPE1', 'MOCK_TYPE2'])->count();
        $this->assertEquals(2, $count);

        $generateSourceForm->sourceTypes = ['MOCK_TYPE3', 'MOCK_TYPE4'];
        $generateSourceForm->timePeriod = 3600;
        $this->assertTrue($generateSourceForm->generate());
        $count = DataSource::find()->dataAccountId(1)->type(['MOCK_TYPE3', 'MOCK_TYPE4'])->count();
        $this->assertEquals(6, $count);
    }
}
