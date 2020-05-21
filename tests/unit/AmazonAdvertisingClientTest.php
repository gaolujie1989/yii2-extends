<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising\tests\unit;

use lujie\amazon\advertising\AmazonadvertisingClient;
use Yii;

class AmazonAdvertisingClientTest extends \Codeception\Test\Unit
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
    public function testListOrders(): void
    {
        $client = new AmazonadvertisingClient([
            'sellerId' => Yii::$app->params['amazon.sellerId'],
            'AWSAccessKeyId' => Yii::$app->params['amazon.AWSAccessKeyId'],
            'AWSSecretAccessKey' => Yii::$app->params['amazon.AWSSecretAccessKey'],
        ]);
        $dateTime = '2020-01-06';
//        $orders = $client->ListOrders([
//            'MarketplaceId.Id.1' => AmazonadvertisingConst::MARKETPLACE_DE,
//            'LastUpdatedAfter' => date('c', strtotime($dateTime)),
//            'LastUpdatedBefore' => date('c', strtotime($dateTime) + 86400)
//        ]);
//        codecept_debug($orders);
        $orders = $client->GetOrder(['AmazonOrderId.id.1' => '406-9986620-9964318']);
        codecept_debug($orders);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function te1stGetReport(): void
    {
        $client = new AmazonadvertisingClient([
            'sellerId' => Yii::$app->params['amazon.sellerId'],
            'AWSAccessKeyId' => Yii::$app->params['amazon.AWSAccessKeyId'],
            'AWSSecretAccessKey' => Yii::$app->params['amazon.AWSSecretAccessKey'],
        ]);
//        $data = $client->GetReportList(['MaxCount' => 100]);
//        codecept_debug($data);
        $report = $client->GetReport(['ReportId' => 19557059339018269]);
        codecept_debug($report);
    }
}
