<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\ebay\tests\unit;

use Codeception\Test\Unit;
use lujie\ebay\EbayRestClient;
use yii\helpers\VarDumper;

/**
 * Class EbayRestClientTest
 * @package lujie\ebay
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class EbayRestClientTest extends Unit
{
    public function testMe(): void
    {
        $client = new EbayRestClient();
        codecept_debug(VarDumper::export($client->methodDoc()));
    }
}
