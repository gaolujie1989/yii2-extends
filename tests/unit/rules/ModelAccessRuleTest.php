<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit\rules;

use lujie\auth\rules\ModelAccessRule;
use lujie\auth\tests\unit\fixtures\TestModel;
use Yii;
use yii\rbac\Item;
use yii\web\Request;

class ModelAccessRuleTest extends \Codeception\Test\Unit
{
    private $oldRequest;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function _before(): void
    {
        $request = new Request();
        $request->setQueryParams(['id' => 1]);
        $this->oldRequest = Yii::$app->get('request');
        Yii::$app->set('request', $request);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function _after(): void
    {
        Yii::$app->set('request', $this->oldRequest);
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        $rule = new ModelAccessRule();
        $rule->modelClass = TestModel::class;
        $rule->condition = ['created_by' => '1'];
        $this->assertTrue($rule->execute(1, new Item(), []));

        $rule->condition = ['created_by' => '>1'];
        $this->assertFalse($rule->execute(1, new Item(), []));
    }
}
