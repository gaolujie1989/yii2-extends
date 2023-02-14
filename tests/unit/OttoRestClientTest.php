<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising\tests\unit;

use lujie\otto\OttoRestClient;
use Yii;
use yii\helpers\Json;

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

//        $products = $ottoRestClient->eachV3Products(['limit' => 2]);
//        $products = iterator_to_array($products);
//        file_put_contents('/app/test.products.json', Json::encode($products));
//        codecept_debug($products);

//        $categories = $ottoRestClient->listV3ProductCategories();
//        file_put_contents('/app/test.categories.json', Json::encode($categories));
//        codecept_debug($categories);

        $brands = $ottoRestClient->listV3ProductBrands();
        file_put_contents('/app/test.brands.json', Json::encode($brands));
        codecept_debug($brands);
    }
}
