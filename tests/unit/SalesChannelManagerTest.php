<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tests\unit;


use lujie\data\loader\ChainedDataLoader;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\constants\StatusConst;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\forms\SalesChannelOrderForm;
use lujie\sales\channel\jobs\CancelSalesChannelOrderJob;
use lujie\sales\channel\jobs\ShipSalesChannelOrderJob;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\SalesChannelManager;
use lujie\sales\channel\tests\unit\mocks\MockSalesChannelLoader;
use Yii;
use yii\helpers\VarDumper;
use yii\queue\PushEvent;
use yii\queue\Queue;

class SalesChannelManagerTest extends \Codeception\Test\Unit
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

    protected function getSalesChannelManager(): SalesChannelManager
    {
        return new SalesChannelManager([
            'salesChannelLoader' => [
                'class' => ChainedDataLoader::class,
                'dataLoaders' => [
                    [
                        'class' => MockSalesChannelLoader::class
                    ]
                ]
            ]
        ]);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testEvents(): void
    {
        $salesChannelManager = $this->getSalesChannelManager();
        $app = Yii::$app;
        $app->set('salesChannelManager', $salesChannelManager);
        $app->get('salesChannelManager');
        $salesChannelManager->bootstrap($app);
        $pushedJobs = [];
        $salesChannelManager->queue->on(Queue::EVENT_BEFORE_PUSH, static function (PushEvent $event) use (&$pushedJobs) {
            $pushedJobs[] = $event->job;
        });

        $salesChannelOrderForm = new SalesChannelOrderForm([
            'sales_channel_account_id' => 1,
            'external_order_key' => 2,
        ]);
        $salesChannelOrderForm->save(false);
        $this->assertEmpty($pushedJobs);

        $salesChannelOrderForm->sales_channel_status = SalesChannelConst::CHANNEL_STATUS_PAID;
        $salesChannelOrderForm->save(false);
        $this->assertEmpty($pushedJobs);

        $salesChannelOrderForm->sales_channel_status = SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED;
        $salesChannelOrderForm->save(false);
        $this->assertCount(1, $pushedJobs);
        /** @var ShipSalesChannelOrderJob $job1 */
        $job1 = $pushedJobs[0];
        $this->assertInstanceOf(ShipSalesChannelOrderJob::class, $job1);
        $this->assertEquals($salesChannelOrderForm->sales_channel_order_id, $job1->salesChannelOrderId);
        $salesChannelOrderForm->refresh();
        $this->assertEquals(ExecStatusConst::EXEC_STATUS_QUEUED, $salesChannelOrderForm->order_pushed_status);
        $this->assertTrue(isset($salesChannelOrderForm->order_pushed_result['jobId']));

        $salesChannelOrderForm->updateAttributes([
            'order_pushed_status' => ExecStatusConst::EXEC_STATUS_SUCCESS,
            'order_pushed_result' => [],
        ]);

        $salesChannelOrderForm->sales_channel_status = SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED;
        $salesChannelOrderForm->save(false);
        $this->assertCount(2, $pushedJobs);
        /** @var ShipSalesChannelOrderJob $job1 */
        $job1 = $pushedJobs[1];
        $this->assertInstanceOf(CancelSalesChannelOrderJob::class, $job1);
        $this->assertEquals($salesChannelOrderForm->sales_channel_order_id, $job1->salesChannelOrderId);
        $salesChannelOrderForm->refresh();
        $this->assertEquals(ExecStatusConst::EXEC_STATUS_QUEUED, $salesChannelOrderForm->order_pushed_status);
        $this->assertTrue(isset($salesChannelOrderForm->order_pushed_result['jobId']));
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function testOrderActionShipCancel(): void
    {
        $salesChannelManager = $this->getSalesChannelManager();
        $salesChannelOrder = new SalesChannelOrder([
            'sales_channel_account_id' => 1,
            'external_order_key' => 2,
        ]);
        $this->assertFalse($salesChannelManager->shipSalesChannelOrder($salesChannelOrder));
        $this->assertEquals(ExecStatusConst::EXEC_STATUS_SKIPPED, $salesChannelOrder->order_pushed_status);

        $salesChannelOrder->sales_channel_status = SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED;
        $this->assertTrue($salesChannelManager->shipSalesChannelOrder($salesChannelOrder));
        $this->assertEquals(ExecStatusConst::EXEC_STATUS_SUCCESS, $salesChannelOrder->order_pushed_status);
        $this->assertEquals(SalesChannelConst::CHANNEL_STATUS_SHIPPED, $salesChannelOrder->sales_channel_status);

        $salesChannelOrder = new SalesChannelOrder([
            'sales_channel_account_id' => 1,
            'external_order_key' => 2,
        ]);
        $this->assertFalse($salesChannelManager->cancelSalesChannelOrder($salesChannelOrder));
        $this->assertEquals(ExecStatusConst::EXEC_STATUS_SKIPPED, $salesChannelOrder->order_pushed_status);

        $salesChannelOrder->sales_channel_status = SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED;
        $this->assertTrue($salesChannelManager->cancelSalesChannelOrder($salesChannelOrder));
        $this->assertEquals(ExecStatusConst::EXEC_STATUS_SUCCESS, $salesChannelOrder->order_pushed_status);
        $this->assertEquals(SalesChannelConst::CHANNEL_STATUS_CANCELLED, $salesChannelOrder->sales_channel_status);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testOrderPull(): void
    {
        $salesChannelManager = $this->getSalesChannelManager();
        $salesChannelManager->pullNewSalesChannelOrders(1);
        $salesChannelManager->pullSalesChannelOrders(1);
        $this->assertEquals(2, SalesChannelOrder::find()->count());
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testRetryAndRetry(): void
    {
        $salesChannelAccount = new SalesChannelAccount([
            'account_id' => 1,
            'name' => 'mock',
            'type' => 'mock',
            'username' => 'mock',
            'status' => StatusConst::STATUS_ACTIVE,
        ]);
        $salesChannelAccount->save(false);
        $salesChannelManager = $this->getSalesChannelManager();
        $app = Yii::$app;
        $app->set('salesChannelManager', $salesChannelManager);
        $app->get('salesChannelManager');
        $salesChannelManager->pullNewSalesChannelOrders($salesChannelAccount->account_id);
        $salesChannelOrders = SalesChannelOrder::find()->indexBy('external_order_key')->all();
        $salesChannelOrders[1]->sales_channel_status = SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED;
        $salesChannelOrders[1]->save(false);
        $salesChannelOrders[2]->sales_channel_status = SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED;
        $salesChannelOrders[2]->save(false);

        $salesChannelManager->pushSalesChannelOrders();
        $query = SalesChannelOrder::find()->andWhere(['order_pushed_status' => ExecStatusConst::EXEC_STATUS_QUEUED]);
        $this->assertEquals(2, $query->count());
    }
}