<?php

namespace lujie\data\center\tests\unit;

use lujie\data\center\ThirdPartClientLoader;
use lujie\data\loader\ArrayDataLoader;
use lujie\plentymarkets\PlentymarketsRestClient;

/**
 * Class ThirdPartSourceLoaderTest
 * @package lujie\data\center\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ThirdPartClientLoaderTest extends \Codeception\Test\Unit
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
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testGet()
    {
        $clientLoader = new ThirdPartClientLoader([
            'clients' => [
                'plentymarkets' => [
                    'class' => PlentymarketsRestClient::class,
                    'apiBaseUrl' => 'url',
                    'username' => 'username',
                    'password' => 'password',
                    'clientId' => 'options.clientId',
                    'clientSecret' => 'options.clientSecret',
                ]
            ],
            'dataLoader' => [
                'class' => ArrayDataLoader::class,
                'data' => [
                    'pm123' => [
                        'client' => 'plentymarkets',
                        'url' => 'http://xxx.com/rest/',
                        'username' => 'usernameXXX',
                        'options' => [
                            'clientId' => 'clientIdXXX',
                        ]
                    ]
                ]
            ],
        ]);

        $this->assertNull($clientLoader->get('pm12'));
        $clientPm123 = new PlentymarketsRestClient([
            'apiBaseUrl' => 'http://xxx.com/rest/',
            'username' => 'usernameXXX',
            'clientId' => 'clientIdXXX',
        ]);
        $this->assertEquals($clientPm123, $clientLoader->get('pm123'));
    }
}
