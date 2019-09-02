<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit;

use lujie\plentyMarkets\PlentyMarketsAdminClient;
use Yii;
use yii\helpers\Console;
use yii\helpers\FileHelper;

class PlentyMarketsAdminClientTest extends \Codeception\Test\Unit
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
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function testDynamicExport(): void
    {
        $pmAdminClient = new PlentyMarketsAdminClient([
            'plentyId' => 27552,
            'username' => Yii::$app->params['pm.username'],
            'password' => Yii::$app->params['pm.password'],
            'requestOptions' => [
//                CURLOPT_PROXY => 'https://outhk.skylinktools.com',
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_TIMEOUT => 120,
            ],
        ]);

        $dir = Yii::getAlias('@uploads/downloads/pm');
        FileHelper::createDirectory($dir);
        Console::startProgress($done = 0, $total = 1);
        for ($done = 0; $done <= $total; $done++) {
            $fileContent = $pmAdminClient->dynamicExport('OrderCompleteAllField', $done * 10, 10);
            $file = 'orders_' . $done . '.csv';
            file_put_contents($dir . '/' . $file, $fileContent);
            Console::updateProgress($done, $total, "Download File {$file}");
        }
        Console::endProgress();
    }
}
