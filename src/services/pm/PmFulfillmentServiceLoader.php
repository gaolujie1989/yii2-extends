<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\services\pm;

use lujie\data\loader\BaseDataLoader;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\plentyMarkets\PlentyMarketsRestClient;
use Yii;
use yii\base\NotSupportedException;

/**
 * Class PmFulfillmentService
 * @package lujie\fulfillment\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PmFulfillmentServiceLoader extends BaseDataLoader
{
    /**
     * @var string
     */
    public $fulfillmentServiceClass = PmFulfillmentService::class;

    /**
     * @var array
     */
    public $fulfillmentServiceConfig = [];

    /**
     * @param int|mixed|string $key
     * @return PmFulfillmentService|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function get($key): ?PmFulfillmentService
    {
        /** @var ?FulfillmentAccount $account */
        $account = FulfillmentAccount::findOne($key);
        if ($account === null || $account->type !== FulfillmentConst::ACCOUNT_TYPE_PM) {
            return null;
        }
        $client = [
            'class' => PlentyMarketsRestClient::class,
            'apiBaseUrl' => $account->url,
            'username' => $account->username,
            'password' => $account->password,
        ];
        /** @var PmFulfillmentService $pmFulfillmentService */
        $pmFulfillmentService = Yii::createObject(array_merge(
            ['class' => $this->fulfillmentServiceClass],
            [
                'account' => $account,
                'client' => $client,
            ],
            $this->fulfillmentServiceConfig,
        ));
        if ($account->options) {
            foreach ($account->options as $property => $value) {
                if ($pmFulfillmentService->hasProperty($property)) {
                    $pmFulfillmentService->{$property} = $value;
                }
            }
        }
        return $pmFulfillmentService;
    }

    /**
     * @return array|null
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function all(): ?array
    {
        throw new NotSupportedException('The method `all` not support for PmFulfillmentServiceLoader');
    }
}
