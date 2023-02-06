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
            'clientId' => 'token-otto-api',
//            'sandbox' => true,
        ]);
        $authToken = $ottoRestClient->authenticateUser(Yii::$app->params['otto.api_id'], Yii::$app->params['otto.password']);
        codecept_debug($authToken);
    }
}
