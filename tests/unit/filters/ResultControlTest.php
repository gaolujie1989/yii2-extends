<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit\rules;

use lujie\auth\filters\ActionResultRule;
use lujie\auth\filters\ResultControl;
use lujie\auth\rules\QueryResultRule;
use lujie\auth\tests\unit\fixtures\TestController;
use lujie\auth\tests\unit\fixtures\TestUser;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Request;
use yii\web\User;

class ResultControlTest extends \Codeception\Test\Unit
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
        Yii::$app->set('user', [
            'class' => User::class,
            'enableSession' => false,
            'identityClass' => TestUser::class,
        ]);
        $resultControl = new ResultControl([
            'rules' => [
                'actionResultRule' => [
                    'class' => ActionResultRule::class,
                    'allow' => true,
                ],
            ],
        ]);
        Yii::$app->attachBehavior('resultControl', $resultControl);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function _after(): void
    {
        Yii::$app->set('request', $this->oldRequest);
        Yii::$app->set('user', null);
        Yii::$app->detachBehavior('resultControl');
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        Yii::$app->controllerMap['test'] = TestController::class;
        /** @var ActiveDataProvider $result */
        $result = Yii::$app->runAction('test/index');
        $this->assertTrue($result->query->count() > 0);

        $testUser = TestUser::findIdentity(1);
        Yii::$app->user->switchIdentity($testUser);
        $rule = new QueryResultRule();
        $role = Yii::$app->authManager->createRole('test_index_result');
        $role->ruleName = $rule->name;
        $role->data = ['rule' => ['condition' => ['version' => 'm000000_000000_base']]];
        Yii::$app->authManager->add($rule);
        Yii::$app->authManager->add($role);
        Yii::$app->authManager->assign($role, $testUser->id);

        /** @var ActiveDataProvider $result */
        $result = Yii::$app->runAction('test/index');
        $this->assertEquals(1, $result->query->count());
    }
}
