<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising\tests\unit;

use lujie\otto\OttoRestClient;
use Yii;

class OttoRestClientTest extends \Codeception\Test\Unit
{
    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $ottoRestClient = new OttoRestClient([
            'username' => Yii::$app->params['otto.api_username'],
            'password' => Yii::$app->params['otto.api_password'],
        ]);
//        $authToken = $ottoRestClient->getAccessToken();
//        codecept_debug($authToken);
        $products = $ottoRestClient->eachV3Products(['limit' => 2]);
        $products = iterator_to_array($products);
        codecept_debug($products);
    }
}
