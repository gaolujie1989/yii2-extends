<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit;

use lujie\auth\forms\AuthRoleForm;

class RoleFormTest extends \Codeception\Test\Unit
{
    /**
     * @inheritdoc
     */
    public function testMe(): void
    {
        //test create
        $roleForm = new AuthRoleForm();
        $roleForm->setAttributes([
            'name' => 'TEST_ROLE',
            'description' => 'TEST_ROLE_DESC',
        ]);
        $this->assertTrue($roleForm->save());

        $role = $roleForm->authManager->getRole('TEST_ROLE');
        $this->assertEquals('TEST_ROLE', $role->name);
        $this->assertEquals('TEST_ROLE_DESC', $role->description);

        //test create again
        $roleForm = new AuthRoleForm();
        $roleForm->setAttributes([
            'name' => 'TEST_ROLE',
            'description' => 'TEST_ROLE_DESC_2',
        ]);
        $this->assertFalse($roleForm->save());
        $this->assertTrue($roleForm->hasErrors('name'));

        //test find and update
        $roleForm = AuthRoleForm::findOne('TEST_ROLE2');
        $this->assertNull($roleForm);
        $roleForm = AuthRoleForm::findOne('TEST_ROLE');
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

    /**
     * @throws \Exception
     */
    public function testSavePermissions(): void
    {
        $roleForm = new AuthRoleForm();
        $authManager = $roleForm->authManager;
        $permission1 = $authManager->createPermission('PERMISSION_1');
        $permission2 = $authManager->createPermission('PERMISSION_2');
        $permission3 = $authManager->createPermission('PERMISSION_3');
        $authManager->add($permission1);
        $authManager->add($permission2);
        $authManager->add($permission3);

        $roleForm->setAttributes([
            'name' => '',
            'description' => 'TEST_ROLE_DESC',
            'permissions' => ['PERMISSION_1', 'PERMISSION_2', 'PERMISSION_NOT_EXIST']
        ]);
        $this->assertFalse($roleForm->save());
        $this->assertTrue($roleForm->hasErrors('name'));
        $this->assertTrue($roleForm->hasErrors('permissions'));

        $roleForm->setAttributes([
            'name' => 'TEST_ROLE',
            'description' => 'TEST_ROLE_DESC',
            'permissions' => ['PERMISSION_1', 'PERMISSION_2']
        ]);
        $this->assertTrue($roleForm->save());
        $role = $authManager->getRole($roleForm->name);
        $this->assertTrue($authManager->hasChild($role, $permission1));
        $this->assertTrue($authManager->hasChild($role, $permission2));
        $this->assertFalse($authManager->hasChild($role, $permission3));

        $roleForm->permissions = ['PERMISSION_1', 'PERMISSION_3'];
        $this->assertTrue($roleForm->save());
        $this->assertTrue($authManager->hasChild($role, $permission1));
        $this->assertFalse($authManager->hasChild($role, $permission2));
        $this->assertTrue($authManager->hasChild($role, $permission3));

        $roleForm->permissions = null;
        $this->assertTrue($roleForm->save());
        $this->assertTrue($authManager->hasChild($role, $permission1));
        $this->assertFalse($authManager->hasChild($role, $permission2));
        $this->assertTrue($authManager->hasChild($role, $permission3));

        $roleForm->permissions = [];
        $this->assertTrue($roleForm->save());
        $this->assertFalse($authManager->hasChild($role, $permission1));
        $this->assertFalse($authManager->hasChild($role, $permission2));
        $this->assertFalse($authManager->hasChild($role, $permission3));
    }
}
