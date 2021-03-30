<?php

namespace lujie\data\storage\tests\unit;

use lujie\data\storage\FileDataStorage;

/**
 * Class PhpArrayFileDataStorageTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PhpArrayFileDataStorageTest extends \Codeception\Test\Unit
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
        $data = [
            'aaa' => 'aaa',
            'bbb' => [
                'ddd' => 'ddd'
            ],
        ];
        $file = __DIR__ . '/fixtures/data.php';
        if (file_exists($file)) {
            unlink($file);
        }
        $dataLoader = new FileDataStorage([
            'file' => $file,
        ]);

        $dataLoader->set('aaa', 'aaa');
        $dataLoader->set('bbb.ddd', 'ddd');
        $this->assertEquals('aaa', $dataLoader->get('aaa'));
        $this->assertEquals('ddd', $dataLoader->get('bbb.ddd'));
        $this->assertNull($dataLoader->get('ccc'));
        $this->assertFileExists($file);
        $this->assertEquals($data, require $file);

        $dataLoader->remove('aaa');
        $this->assertNull($dataLoader->get('aaa'));
        $dataLoader->remove('bbb.ddd');
        $this->assertNull($dataLoader->get('bbb.ddd'));
    }
}
