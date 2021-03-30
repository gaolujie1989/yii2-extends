<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\tests\unit\mocks\MockActiveRecord;
use Yii;

class DbConnectionTraitTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
        Yii::$app->set('testDB', [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=mysql;dbname=yii2ext_test',
            'username' => 'test',
            'password' => 'test',
            'charset' => 'utf8mb4',
            'enableSchemaCache' => true,
        ]);
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
        $db = MockActiveRecord::getDb();
        $this->assertEquals(Yii::$app->db, $db);

        Yii::$app->params['modelDBs'][MockActiveRecord::class] = 'testDB';
        $db = MockActiveRecord::getDb();
        $this->assertEquals(Yii::$app->get('testDB'), $db);
    }
}
