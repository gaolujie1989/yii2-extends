<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\pm;

use lujie\otto\OttoRestClient;
use lujie\sales\channel\BaseSalesChannelLoader;
use lujie\sales\channel\models\SalesChannelAccount;

/**
 * Class PmSalesChannelLoader
 * @package lujie\sales\channel\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoSalesChannelLoader extends BaseSalesChannelLoader
{
    /**
     * @param SalesChannelAccount $account
     * @return array[]
     * @inheritdoc
     */
    protected function getConfig(SalesChannelAccount $account): array
    {
        return [
            'client' => [
                'class' => OttoRestClient::class,
                'username' => $account->username,
                'password' => $account->password,
            ]
        ];
    }
}
