<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit;

use lujie\data\staging\DataSourceCreator;
use lujie\data\staging\forms\DataAccountForm;
use lujie\data\staging\models\DataSource;
use lujie\data\staging\DataSourceModelStorage;
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
            'additional_info' => ['yy' => 'yy'],
            'status' => 0,
        ]);
        $this->assertTrue($dataAccount->save(false));
        $count = DataSource::find()->andWhere(['data_account_id' => $dataAccount->data_account_id])->count();
        $this->assertEquals(2, $count);

        $this->assertTrue($dataAccount->delete());
        $count = DataSource::find()->andWhere(['data_account_id' => $dataAccount->data_account_id])->count();
        $this->assertEquals(0, $count);
    }
}
