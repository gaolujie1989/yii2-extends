<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\otto;

use lujie\sales\channel\BaseSalesChannelLoader;
use lujie\sales\channel\channels\shopify\ShopifySalesChannel;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\shopify\ShopifyAdminRestClient;
use lujie\shopify\ShopifyRestClient;

/**
 * Class PmSalesChannelLoader
 * @package lujie\sales\channel\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShopifySalesChannelLoader extends BaseSalesChannelLoader
{
    public $salesChannelAccountType = SalesChannelConst::ACCOUNT_TYPE_SHOPIFY;

    public $salesChannelClass = ShopifySalesChannel::class;

    /**
     * @param SalesChannelAccount $account
     * @return array[]
     * @inheritdoc
     */
    protected function getConfig(SalesChannelAccount $account): array
    {
        return [
            'client' => [
                'class' => ShopifyAdminRestClient::class,
                'apiKey' => $account->username,
                'apiSecret' => $account->password,
            ]
        ];
    }
}
