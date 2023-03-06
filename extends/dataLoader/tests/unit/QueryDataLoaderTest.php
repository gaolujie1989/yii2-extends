<?php

namespace lujie\data\loader\tests\unit;

use lujie\data\loader\QueryDataLoader;
use yii\db\Query;

/**
 * Class DbDataLoaderTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueryDataLoaderTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testMe(): void
    {
        $baseMigrationVersion = 'm000000_000000_base';
        $dataLoader = new QueryDataLoader([
            'query' => (new Query())->from('{{%migration}}'),
            'key' => 'version',
        ]);

        $version = $dataLoader->get($baseMigrationVersion);
        $this->assertIsArray($version);
        $this->assertEquals($baseMigrationVersion, $version['version']);
        $this->assertEmpty($dataLoader->get('ccc'));

        $all = $dataLoader->all();
        $count = count($all);
        $this->assertTrue($count >= 1);

        $dataLoader->condition = ['version' => $baseMigrationVersion];
        $this->assertCount(1, $dataLoader->all());

        $dataLoader->value = 'version';
        $this->assertEquals($baseMigrationVersion, $dataLoader->get($baseMigrationVersion));
    }
}
