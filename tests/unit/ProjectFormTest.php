<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\tests\unit;

use lujie\project\forms\ProjectForm;
use lujie\project\models\Project;
use lujie\project\models\TaskGroup;

class ProjectFormTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testMe(): void
    {
        $projectQuery = Project::find();
        $taskGroupQuery = TaskGroup::find();

        $projectForm = new ProjectForm();
        $projectForm->setAttributes(['name' => 'testProject']);
        $this->assertTrue($projectForm->save());
        $existProject = $projectQuery->one();
        $this->assertEquals('testProject', $existProject->name);
        $this->assertEquals(0, $taskGroupQuery->count());
        $this->assertEquals(1, $projectForm->delete());

        $projectForm = new ProjectForm();
        $projectForm->setAttributes(['name' => 'defaultProject', 'template' => 'DEFAULT']);
        $this->assertTrue($projectForm->save());
        $existProject = $projectQuery->one();
        $this->assertEquals('defaultProject', $existProject->name);
        $this->assertEquals(1, $taskGroupQuery->count());
        $this->assertEquals(1, $projectForm->delete());
        $this->assertEquals(0, $taskGroupQuery->count());

        $projectForm = new ProjectForm();
        $projectForm->setAttributes(['name' => 'simpleProcessProject', 'template' => 'SIMPLE_PROCESS']);
        $this->assertTrue($projectForm->save());
        $existProject = $projectQuery->one();
        $this->assertEquals('simpleProcessProject', $existProject->name);
        $this->assertEquals(3, $taskGroupQuery->count());
        $this->assertEquals(1, $projectForm->delete());
        $this->assertEquals(0, $taskGroupQuery->count());
    }
}
