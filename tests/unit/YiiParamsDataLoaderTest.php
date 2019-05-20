<?php

namespace lujie\data\loader\tests\unit;

use lujie\data\loader\YiiParamsDataLoader;
use Yii;

/**
 * Class YiiParamsDataLoaderTest
 * @package tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class YiiParamsDataLoaderTest extends \Codeception\Test\Unit
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
        $paramKey = 'xyz.xxx123';
        Yii::$app->params['xyz']['xxx123'] = $data;
        $dataLoader = new YiiParamsDataLoader([
            'paramKey' => $paramKey,
        ]);

        $this->assertEquals($data, $dataLoader->all());
        $this->assertEquals($data['aaa'], $dataLoader->get('aaa'));
        $this->assertEquals($data['bbb']['ddd'], $dataLoader->get('bbb.ddd'));
        $this->assertNull($dataLoader->get('ccc'));
    }
}
