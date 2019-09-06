<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit;

use lujie\data\recording\DataSourceCreator;
use lujie\data\recording\forms\DataAccountForm;
use lujie\data\recording\models\DataSource;
use lujie\data\recording\DataSourceModelStorage;
use Yii;

class DataSourceCreatorTest extends \Codeception\Test\Unit
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
        $dataSourceCreator = new DataSourceCreator([
            'accountType' => 'testType',
            'sourceTypes' => ['testSourceType1', 'testSourceType2']
        ]);
        $dataSourceCreator->bootstrap(Yii::$app);
        $dataAccount = new DataAccountForm([
            'name' => 'testName',
            'type' => 'testType',
            'url' => 'http://xxx.xx',
            'username' => 'xxu',
            'password' => 'xxp',
            'options' => ['xx' => 'xx'],
            'additional' => ['yy' => 'yy'],
            'status' => 0,
        ]);
        $this->assertTrue($dataAccount->save(false));
        $query = DataSource::find()->andWhere(['data_account_id' => $dataAccount->data_account_id]);
        $this->assertEquals(2, $query->count());

        $dataAccount->refresh();
        $this->assertEquals(1, $dataAccount->delete());
        $this->assertEquals(0, $query->count());
    }
}
