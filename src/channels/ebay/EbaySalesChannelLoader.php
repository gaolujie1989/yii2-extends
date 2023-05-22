<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\ebay;

use lujie\common\oauth\models\AuthToken;
use lujie\ebay\EbayRestClient;
use lujie\otto\OttoRestClient;
use lujie\sales\channel\BaseSalesChannelLoader;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelAccount;

/**
 * Class PmSalesChannelLoader
 * @package lujie\sales\channel\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class EbaySalesChannelLoader extends BaseSalesChannelLoader
{
    public $salesChannelAccountType = SalesChannelConst::ACCOUNT_TYPE_EBAY;

    public $salesChannelClass = EbayRestClient::class;

    public $clientId;

    public $clientSecret;

    /**
     * @param SalesChannelAccount $account
     * @return array[]
     * @inheritdoc
     */
    protected function getConfig(SalesChannelAccount $account): array
    {
        $config = [
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ];
        $client = new EbayRestClient($config);
        $accountId = $account->account_id;
        $client->setId($client->getName() . '-' . $accountId);
        $client->setSandbox(str_contains($account->type, 'Sandbox'));
        if (!$client->getAccessToken()) {
            $authToken = AuthToken::find()->userId($accountId)->one();
            $client->setAccessToken([
                'params' => $authToken->additional['token'],
                'createTimestamp' => $authToken->updated_at,
            ]);
        }

        return [
            'client' => $client,
        ];
    }
}
