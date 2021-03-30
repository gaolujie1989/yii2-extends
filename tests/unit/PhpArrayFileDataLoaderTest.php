<?php

namespace lujie\data\loader\tests\unit;

use lujie\data\loader\FileDataLoader;

/**
 * Class PhpArrayFileDataLoaderTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PhpArrayFileDataLoaderTest extends \Codeception\Test\Unit
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
        $data = require __DIR__ . '/fixtures/data.php';
        $dataLoader = new FileDataLoader([
            'filePools' => [__DIR__],
            'filePathTemplate' => '{filePool}/fixtures/data.php'
        ]);

        $this->assertEquals($data, $dataLoader->all());
        $this->assertEquals($data['aaa'], $dataLoader->get('aaa'));
        $this->assertEquals($data['bbb']['ddd'], $dataLoader->get('bbb.ddd'));
        $this->assertNull($dataLoader->get('ccc'));
    }
}
