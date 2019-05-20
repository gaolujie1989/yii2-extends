<?php

namespace tests\unit;

use lujie\data\loader\TypedFileDataLoader;

/**
 * Class PhpArrayTypedFileDataLoaderTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PhpArrayTypedFileDataLoaderTest extends \Codeception\Test\Unit
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

    // tests
    public function testMe(): void
    {
        $dataLoader = new TypedFileDataLoader([
            'filePools' => [__DIR__],
            'typedFilePathTemplate' => '{filePool}/fixtures/*/config/{type}.php',
        ]);

        $permissions = array_merge(
            require __DIR__ . '/fixtures/module1/config/permission.php',
            require __DIR__ . '/fixtures/module2/config/permission.php'
        );
        $tasks = array_merge(
            require __DIR__ . '/fixtures/module1/config/task.php',
            require __DIR__ . '/fixtures/module2/config/task.php'
        );
        $all = ['permission' => $permissions, 'task' => $tasks];

        $this->assertEquals($permissions, $dataLoader->get('permission'));
        $this->assertEquals($tasks, $dataLoader->get('task'));
        $this->assertEquals(null, $dataLoader->get('ccc'));
        $this->assertEquals($all, $dataLoader->all());
    }
}
