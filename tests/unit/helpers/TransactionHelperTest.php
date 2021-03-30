<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\helpers\TransactionHelper;
use lujie\extend\tests\unit\fixtures\models\Migration;
use yii\db\Exception;

class TransactionHelperTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $query = Migration::find()->andWhere(['version' => 'm_test']);
        $migration = new Migration(['version' => 'm_test', 'apply_time' => 123456789]);

        $this->assertFalse($query->exists());
        $transaction = TransactionHelper::transaction(static function () use ($migration) {
            $migration->setIsNewRecord(true);
            $migration->save(false);
            return false;
        }, Migration::getDb());
        $this->assertFalse($transaction);
        $this->assertFalse($query->exists());

        $transaction = TransactionHelper::transaction(static function () use ($migration) {
            $migration->setIsNewRecord(true);
            return $migration->save(false);
        }, Migration::getDb());
        $this->assertTrue($transaction);
        $this->assertTrue($query->exists());

        $this->assertEquals(1, $migration->delete());
        $this->expectException(Exception::class);
        try {
            TransactionHelper::transaction(static function () use ($migration) {
                $migration->setIsNewRecord(true);
                $migration->save(false);
                throw new Exception('xxx');
            }, Migration::getDb());
        } catch (\Exception $exception) {
            throw $exception;
        } finally {
            $this->assertFalse($query->exists());
        }
    }
}
