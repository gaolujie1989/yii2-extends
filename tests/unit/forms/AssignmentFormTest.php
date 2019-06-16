<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit;


use function Complex\theta;
use lujie\auth\forms\AssignmentForm;
use lujie\auth\tests\unit\fixtures\TestUser;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class AssignmentFormTest extends \Codeception\Test\Unit
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
        $assignmentForm = new AssignmentForm([
            'userClass' => TestUser::class
        ]);

        $authManager = $assignmentForm->authManager;
        $authManager->add($authManager->createRole('TEST_ROLE1'));
        $authManager->add($authManager->createRole('TEST_ROLE2'));
        $authManager->add($authManager->createRole('TEST_ROLE3'));

        //test assign
        $assignmentForm->setAttributes([
            'userId' => 0,
            'itemNames' => ['TEST_ROLE1', 'TEST_ROLE2', 'TEST_ROLE11'],
        ]);
        $this->assertFalse($assignmentForm->assign());
        $this->assertTrue($assignmentForm->hasErrors('userId'));
        $this->assertTrue($assignmentForm->hasErrors('itemNames'));

        $assignmentForm->setAttributes([
            'userId' => 1,
            'itemNames' => ['TEST_ROLE1', 'TEST_ROLE2'],
        ]);
        $this->assertTrue($assignmentForm->assign());
        $assignedItemNames = array_values(ArrayHelper::getColumn($authManager->getAssignments(1), 'roleName'));
        $this->assertEquals(['TEST_ROLE1', 'TEST_ROLE2'], $assignedItemNames);

        //test revoke
        $assignmentForm->setAttributes([
            'userId' => 0,
            'itemNames' => ['TEST_ROLE3'],
        ]);
        $this->assertFalse($assignmentForm->revoke());
        $this->assertTrue($assignmentForm->hasErrors('userId'));
        $this->assertTrue($assignmentForm->hasErrors('itemNames'));

        $assignmentForm->setAttributes([
            'userId' => 1,
            'itemNames' => ['TEST_ROLE2'],
        ]);
        $this->assertTrue($assignmentForm->revoke());
        $assignedItemNames = array_values(ArrayHelper::getColumn($authManager->getAssignments(1), 'roleName'));
        $this->assertEquals(['TEST_ROLE1'], $assignedItemNames);

        //test save
        $assignmentForm->setAttributes([
            'userId' => 1,
            'itemNames' => ['TEST_ROLE2', 'TEST_ROLE3'],
        ]);
        $this->assertTrue($assignmentForm->save());
        $assignedItemNames = array_values(ArrayHelper::getColumn($authManager->getAssignments(1), 'roleName'));
        $this->assertEquals(['TEST_ROLE2', 'TEST_ROLE3'], $assignedItemNames);
    }
}
