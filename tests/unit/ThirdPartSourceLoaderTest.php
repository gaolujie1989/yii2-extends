<?php

namespace lujie\data\center\tests\unit;

use lujie\data\center\ThirdPartClientLoader;
use lujie\data\center\ThirdPartSourceLoader;
use lujie\data\exchange\sources\RestSource;
use lujie\data\loader\ArrayDataLoader;
use lujie\plentymarkets\PlentymarketsRestClient;

/**
 * Class ThirdPartSourceLoaderTest
 * @package lujie\data\center\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ThirdPartSourceLoaderTest extends \Codeception\Test\Unit
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
    public function testMe()
    {
        $sourceLoader = new ThirdPartSourceLoader([
            'sources' => [
                'rest' => [
                    'class' => RestSource::class,
                    'client' => 'client',
                    'resource' => 'options.resource',
                ]
            ],
            'dataLoader' => [
                'class' => ArrayDataLoader::class,
                'data' => [
                    'pm123_order' => [
                        'client' => 'pm123',
                        'source' => 'rest',
                        'type' => 'plentymarkets_order',
                        'options' => [
                            'resource' => 'orders',
                        ]
                    ]
                ]
            ],
            'clientLoader' => [
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
            ],
        ]);

        $this->assertNull($sourceLoader->get('pm12'));
        $clientPm123 = new PlentymarketsRestClient([
            'apiBaseUrl' => 'http://xxx.com/rest/',
            'username' => 'usernameXXX',
            'clientId' => 'clientIdXXX',
        ]);
        $sourcePm123Order = new RestSource([
            'client' => $clientPm123,
            'resource' => 'orders',
        ]);
        $this->assertEquals($sourcePm123Order, $sourceLoader->get('pm123_order'));
    }
}
