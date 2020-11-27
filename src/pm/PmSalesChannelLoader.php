<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\pm;

use lujie\data\loader\BaseDataLoader;
use lujie\plentyMarkets\PlentyMarketsRestClient;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelAccount;
use Yii;
use yii\base\NotSupportedException;

/**
 * Class PmSalesChannelLoader
 * @package lujie\fulfillment\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PmSalesChannelLoader extends BaseDataLoader
{
    /**
     * @var string
     */
    public $salesChannelClass = PmSalesChannel::class;

    /**
     * @var array
     */
    public $salesChannelConfig = [];

    /**
     * @param int|mixed|string $key
     * @return PmFulfillmentService|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function get($key): ?PmSalesChannel
    {
        /** @var ?SalesChannelAccount $account */
        $account = SalesChannelAccount::findOne($key);
        if ($account === null || $account->type !== SalesChannelConst::ACCOUNT_TYPE_PM) {
            return null;
        }
        $client = [
            'class' => PlentyMarketsRestClient::class,
            'apiBaseUrl' => $account->url,
            'username' => $account->username,
            'password' => $account->password,
        ];
        /** @var PmSalesChannel $pmSalesChannel */
        $pmSalesChannel = Yii::createObject(array_merge(
            ['class' => $this->salesChannelClass],
            [
                'account' => $account,
                'client' => $client,
            ],
            $this->salesChannelConfig,
        ));
        if ($account->options) {
            foreach ($account->options as $property => $value) {
                if ($pmSalesChannel->hasProperty($property)) {
                    $pmSalesChannel->{$property} = $value;
                }
            }
        }
        return $pmSalesChannel;
    }

    /**
     * @return array|null
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function all(): ?array
    {
        throw new NotSupportedException('The method `all` not support for PmSalesChannelLoader');
    }
}
