<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\services\pm;

use lujie\fulfillment\BaseFulfillmentServiceLoader;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\plentyMarkets\PlentyMarketsRestClient;

/**
 * Class PmFulfillmentService
 * @package lujie\fulfillment\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PmFulfillmentServiceLoader extends BaseFulfillmentServiceLoader
{
    public $fulfillmentServiceAccountType = FulfillmentConst::ACCOUNT_TYPE_PM;

    public $fulfillmentServiceClass = PmFulfillmentService::class;

    /**
     * @param FulfillmentAccount $account
     * @return array[]
     * @inheritdoc
     */
    protected function getConfig(FulfillmentAccount $account): array
    {
        return [
            'client' => [
                'class' => PlentyMarketsRestClient::class,
                'apiBaseUrl' => $account->url,
                'username' => $account->username,
                'password' => $account->password,
            ]
        ];
    }
}
