<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit;

use lujie\auth\forms\RoleForm;

class RoleFormTest extends \Codeception\Test\Unit
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
        //test create
        $roleForm = new RoleForm();
        $roleForm->setAttributes([
            'name' => 'TEST_ROLE',
            'description' => 'TEST_ROLE_DESC',
        ]);
        $this->assertTrue($roleForm->save());

        $role = $roleForm->authManager->getRole('TEST_ROLE');
        $this->assertEquals('TEST_ROLE', $role->name);
        $this->assertEquals('TEST_ROLE_DESC', $role->description);

        //test update
        $roleForm = new RoleForm();
        $roleForm->setAttributes([
            'name' => 'TEST_ROLE',
            'description' => 'TEST_ROLE_DESC_2',
        ]);
        $this->assertFalse($roleForm->save());
        $this->assertTrue($roleForm->hasErrors('name'));

        //test find and update
        $roleForm = RoleForm::findOne('TEST_ROLE2');
        $this->assertNull($roleForm);
        $roleForm = RoleForm::findOne('TEST_ROLE');
        $this->assertNotNull($roleForm);

        $roleForm->setAttributes([
            'name' => 'TEST_ROLE_2',
            'description' => 'TEST_ROLE_DESC_2',
        ]);
        $this->assertTrue($roleForm->save());

        $role = $roleForm->authManager->getRole('TEST_ROLE_2');
        $this->assertEquals('TEST_ROLE_2', $role->name);
        $this->assertEquals('TEST_ROLE_DESC_2', $role->description);
    }
}
