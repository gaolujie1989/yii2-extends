<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\services\f4px;

use lujie\fulfillment\BaseFulfillmentServiceLoader;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentAccount;

/**
 * Class PmFulfillmentService
 * @package lujie\fulfillment\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class F4pxFulfillmentServiceLoader extends BaseFulfillmentServiceLoader
{
    public $fulfillmentServiceAccountType = FulfillmentConst::ACCOUNT_TYPE_F4PX;

    public $fulfillmentServiceClass = F4pxFulfillmentService::class;

    /**
     * @param FulfillmentAccount $account
     * @return array[]
     * @inheritdoc
     */
    protected function getConfig(FulfillmentAccount $account): array
    {
        return [
            'client' => [
                'class' => F4pxClient::class,
                'appKey' => $account->username,
                'appSecret' => $account->password,
//                'sandbox' => true,
            ]
        ];
    }
}
