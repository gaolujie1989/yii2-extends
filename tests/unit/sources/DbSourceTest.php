<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\tests\unit\sources;

use lujie\data\exchange\sources\DbSource;

class DbSourceTest extends \Codeception\Test\Unit
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
        $baseMigrationVersion = 'm000000_000000_base';

        $dbSource = new DbSource([
            'table' => '{{%migration}}',
            'condition' => ['version' => $baseMigrationVersion],
        ]);

        $all = $dbSource->all();
        $this->assertCount(1, $all);
        $this->assertEquals($baseMigrationVersion, $all[0]['version']);
    }
}
