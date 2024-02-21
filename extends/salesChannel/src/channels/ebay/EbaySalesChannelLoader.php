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
use yii\base\InvalidArgumentException;

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
     * @return EbayRestClient[]
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function getConfig(SalesChannelAccount $account): array
    {
        $accountId = $account->account_id;
        $config = [
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ];
        $client = new EbayRestClient($config);
        $client->setId($client->getName() . '-' . $accountId);
        $client->setSandbox(str_contains($account->type, 'Sandbox'));

        $authToken = AuthToken::find()->userId($accountId)->one();
        if ($authToken->refresh_token_expires_at && $authToken->refresh_token_expires_at < time()) {
            throw new InvalidArgumentException('Refresh token is expired');
        }
        $client->setAccessTokenIfTokenIsValid([
            'params' => $authToken->additional['token'],
            'createTimestamp' => $authToken->created_at,
        ]);
        return [
            'client' => $client,
        ];
    }
}
