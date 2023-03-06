<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit;

use lujie\auth\forms\AuthAssignmentForm;
use lujie\auth\tests\unit\fixtures\TestUser;
use yii\helpers\ArrayHelper;

class AuthAssignmentFormTest extends \Codeception\Test\Unit
{
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
        $assignmentForm = new AuthAssignmentForm([
            'userClass' => TestUser::class
        ]);

        $authManager = $assignmentForm->authManager;
        $authManager->add($authManager->createRole('TEST_ROLE_1'));
        $authManager->add($authManager->createRole('TEST_ROLE_2'));
        $authManager->add($authManager->createRole('TEST_ROLE_3'));

        //test assign
        $assignmentForm->setAttributes([
            'userId' => 0,
            'roles' => ['TEST_ROLE_1', 'TEST_ROLE_2', 'TEST_ROLE_NOT_EXIST'],
        ]);
        $this->assertFalse($assignmentForm->assign());
        $this->assertTrue($assignmentForm->hasErrors('userId'));
        $this->assertTrue($assignmentForm->hasErrors('roles'));

        $assignmentForm->setAttributes([
            'userId' => 1,
            'roles' => ['TEST_ROLE_1', 'TEST_ROLE_2'],
        ]);
        $this->assertTrue($assignmentForm->assign());
        $assignedRoles = array_values(ArrayHelper::getColumn($authManager->getAssignments(1), 'roleName'));
        $this->assertEquals(['TEST_ROLE_1', 'TEST_ROLE_2'], $assignedRoles);

        $assignmentForm->roles = ['TEST_ROLE_1', 'TEST_ROLE_3'];
        $this->assertTrue($assignmentForm->assign());
        $assignedRoles = array_values(ArrayHelper::getColumn($authManager->getAssignments(1), 'roleName'));
        $this->assertEquals(['TEST_ROLE_1', 'TEST_ROLE_3'], $assignedRoles);

        $assignmentForm->roles = null;
        $this->assertTrue($assignmentForm->assign());
        $assignedRoles = array_values(ArrayHelper::getColumn($authManager->getAssignments(1), 'roleName'));
        $this->assertEquals(['TEST_ROLE_1', 'TEST_ROLE_3'], $assignedRoles);

        $assignmentForm->roles = [];
        $this->assertTrue($assignmentForm->assign());
        $this->assertEmpty($authManager->getAssignments(1));
    }
}
