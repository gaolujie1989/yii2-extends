<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\services\f4px;

use lujie\data\loader\BaseDataLoader;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentAccount;
use Yii;
use yii\base\NotSupportedException;

/**
 * Class PmFulfillmentService
 * @package lujie\fulfillment\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class F4pxFulfillmentServiceLoader extends BaseDataLoader
{
    /**
     * @var string
     */
    public $fulfillmentServiceClass = F4pxFulfillmentService::class;

    /**
     * @var array
     */
    public $fulfillmentServiceConfig = [];

    /**
     * @param int|mixed|string $key
     * @return F4pxFulfillmentService|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function get($key): ?F4pxFulfillmentService
    {
        /** @var ?FulfillmentAccount $account */
        $account = FulfillmentAccount::findOne($key);
        if ($account === null || $account->type !== FulfillmentConst::ACCOUNT_TYPE_F4PX) {
            return null;
        }
        $client = [
            'class' => F4pxClient::class,
            'appKey' => $account->username,
            'appSecret' => $account->password,
//            'sandbox' => true,
        ];
        /** @var F4pxFulfillmentService $pmFulfillmentService */
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
        throw new NotSupportedException('The method `all` not support for F4pxFulfillmentServiceLoader');
    }
}
