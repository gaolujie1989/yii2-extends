<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\tests\unit;


use lujie\extend\constants\StatusConst;
use lujie\user\forms\UserForm;
use lujie\user\models\User;

class UserFormTest extends \Codeception\Test\Unit
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
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $userForm = new UserForm([
            'username' => 'test_user',
            'password' => 'pass123',
            'email' => 'test_user@xxx.com',
            'status' => StatusConst::STATUS_ACTIVE,
        ]);
        $this->assertTrue($userForm->save(false));
        $user = User::findOne(['username' => 'test_user']);
        $this->assertNotNull($user);
        $this->assertNotEmpty($user->auth_key);
        $this->assertNotEmpty($user->password_hash);
        $this->assertTrue($user->validatePassword('pass123'));
    }
}
