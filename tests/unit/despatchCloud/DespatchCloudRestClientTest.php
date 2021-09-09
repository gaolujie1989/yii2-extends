<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\despatchCloud;

use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\despatchCloud\DespatchCloudRestClient;
use PHPUnit\Framework\Assert;
use Yii;

class DespatchCloudRestClientTest extends \Codeception\Test\Unit
{
    /**
     * @return BaseFulfillmentService
     * @inheritdoc
     */
    protected function getClient(): DespatchCloudRestClient
    {
        return new DespatchCloudRestClient([
            'apiBaseUrl' => Yii::$app->params['despatchCloud.url'],
            'username' => Yii::$app->params['despatchCloud.username'],
            'password' => Yii::$app->params['despatchCloud.password'],
        ]);
    }

    public function te1stMethodDocs(): void
    {
        $client = $this->getClient();
        codecept_debug($client->methodDoc());
    }

    public function testListInventories(): void
    {
        $client = $this->getClient();
        $inventories = $client->listInventories();
        codecept_debug($inventories);
        Assert::assertNotEmpty($inventories);
    }
}
