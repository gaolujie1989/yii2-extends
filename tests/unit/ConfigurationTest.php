<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\configuration\tests\unit;

use lujie\configuration\Configuration;
use lujie\configuration\tests\unit\fixtures\TestModel;
use lujie\data\loader\TypedFileDataLoader;
use Yii;
use yii\base\Event;
use yii\base\Model;
use yii\db\BaseActiveRecord;

class ConfigurationTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $app = Yii::$app;
        $configuration = new Configuration([
            'configLoader' => [
                'class' => TypedFileDataLoader::class,
                'filePools' => [__DIR__  . '/fixtures'],
                'typedFilePathTemplate' => '{filePool}/*/config/{type}.php',
            ],
            'currentScope' => 'console',
            'cache' => false,
        ]);
        $configuration->bootstrap($app);

        //test load class rewrite config
        /** @var TestModel $model */
        $model = Yii::createObject(Model::class);
        $this->assertInstanceOf(TestModel::class, $model);
        $this->assertEquals(1, $model->testP);

        //test load component config
        $components = $app->getComponents();
        $testDbConfig = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql;dbname=test_console',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4',
            'enableSchemaCache' => true,
        ];
        $this->assertEquals($testDbConfig, $components['testDb']);

        //test load all config
        $tasksConfig = [
            'taskA' => [
                'class' => 'xxxClass',
            ]
        ];
        $this->assertEquals($tasksConfig, $app->params['tasks']);

        //test load main config
        $this->assertEquals('Asia/Tokyo', $app->timeZone);

        //test register events
        $this->assertTrue(Event::hasHandlers(BaseActiveRecord::class, BaseActiveRecord::EVENT_AFTER_INSERT));

        //test bootstrap
        $this->assertEquals('executed', Yii::$app->params['xxxSetParams']);
    }
}
