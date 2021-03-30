<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit\sources;

use lujie\data\exchange\sources\ActiveRecordSource;
use lujie\data\exchange\tests\unit\fixtures\Migration;

class ActiveRecordSourceTest extends \Codeception\Test\Unit
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
        $baseMigrationVersion = 'm000000_000000_base';

        $dbSource = new ActiveRecordSource([
            'modelClass' => Migration::class,
            'condition' => ['version' => $baseMigrationVersion],
            'asArray' => true,
        ]);

        $all = $dbSource->all();
        $this->assertCount(1, $all);
        $this->assertIsArray($all[0]);
        $this->assertEquals($baseMigrationVersion, $all[0]['version']);

        $dbSource = new ActiveRecordSource([
            'modelClass' => Migration::class,
            'condition' => ['version' => $baseMigrationVersion],
            'asArray' => false,
        ]);

        $all = $dbSource->all();
        $this->assertCount(1, $all);
        $this->assertInstanceOf(Migration::class, $all[0]);
        $this->assertEquals($baseMigrationVersion, $all[0]['version']);
    }
}
