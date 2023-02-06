<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\pm;

use lujie\plentyMarkets\PlentyMarketsRestClient;
use lujie\sales\channel\BaseSalesChannelLoader;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelAccount;

/**
 * Class PmSalesChannelLoader
 * @package lujie\sales\channel\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PmSalesChannelLoader extends BaseSalesChannelLoader
{
    public $salesChannelAccountType = SalesChannelConst::ACCOUNT_TYPE_PM;

    public $salesChannelClass = PmSalesChannel::class;

    /**
     * @param SalesChannelAccount $account
     * @return array[]
     * @inheritdoc
     */
    protected function getConfig(SalesChannelAccount $account): array
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
