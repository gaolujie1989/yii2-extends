<?php

namespace tests\unit;

use lujie\data\storage\FileDataStorage;

/**
 * Class PhpArrayFileDataStorageTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PhpArrayFileDataStorageTest extends \Codeception\Test\Unit
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
        $data = [
            'aaa' => 'aaa',
            'bbb' => [
                'ddd' => 'ddd'
            ],
        ];
        $file = __DIR__ . '/fixtures/data.php';
        $dataLoader = new FileDataStorage([
            'file' => $file,
        ]);

        $dataLoader->set('aaa', 'aaa');
        $dataLoader->set('bbb.ddd', 'ddd');
        $this->assertEquals('aaa', $dataLoader->get('aaa'));
        $this->assertEquals('ddd', $dataLoader->get('bbb.ddd'));
        $this->assertFileExists($file);
        $this->assertEquals($data, require $file);
    }
}
