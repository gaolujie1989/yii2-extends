<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\despatchCloud;

use lujie\extend\authclient\SimpleOAuth;
use yii\authclient\OAuthToken;

/**
 * Class DespatchCloudRestClient
 *
 * @method array listOrders($data = [])
 * @method \Generator eachOrders($condition = [], $batchSize = 100)
 * @method \Generator batchOrders($condition = [], $batchSize = 100)
 * @method array getOrder($data)
 * @method array createOrder($data)
 * @method array updateOrder($data)
 * @method array deleteOrder($data)
 *
 * @method array listOrderItems($data = [])
 * @method \Generator eachOrderItems($condition = [], $batchSize = 100)
 * @method \Generator batchOrderItems($condition = [], $batchSize = 100)
 * @method array getOrderItem($data)
 * @method array createOrderItem($data)
 * @method array updateOrderItem($data)
 * @method array deleteOrderItem($data)
 *
 * @method array listInventories($data = [])
 * @method \Generator eachInventories($condition = [], $batchSize = 100)
 * @method \Generator batchInventories($condition = [], $batchSize = 100)
 * @method array getInventory($data)
 * @method array createInventory($data)
 * @method array updateInventory($data)
 * @method array deleteInventory($data)
 *
 * @package lujie\fulfillment\despatchCloud
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @document https://documenter.getpostman.com/view/14780146/TzRVfRq4#df84dbcc-4ad5-4e32-a000-bdacabd13242
 */
class DespatchCloudRestClient extends SimpleOAuth
{
    public $apiBaseUrl = 'https://your-despatchcloud-domain/public-api/';

    public $authUrl = 'auth/login';

    public $userUrl = 'auth/verify';

    public $resources = [
        'Order' => 'orders',
        'OrderItem' => 'order/{orderId}/inventory??',
        'Inventory' => 'inventory'
    ];

    public $extraActions = [
        'OrderItem' => [
            'create' => ['POST', 'order/{orderId}/add_inventory'],
            'update' => ['POST', 'order/{orderId}/update_inventory'],
            'delete' => ['DELETE', 'order/{orderId}/remove_inventory'],
        ]
    ];

    #region pagination

    public $requestPageSizeKey = '';

    public $responsePageKey = 'page';

    public $responsePageCountKey = 'last_page';

    public $responsePageSizeKey = 'per_page';

    public $responseTotalCountKey = 'total';

    public $responseNextLinksKey = 'next_page_url';

    #endregion

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        $this->actions = [
            'list' => ['GET', ''],
            'get' => ['GET', '{id}'],
            'create' => ['POST', 'create'],
            'update' => ['POST', '{id}/update'],
            'delete' => ['DELETE', '{id}/delete'],
        ];
        parent::init();
        $this->initRest();
    }

    #region BaseOAuth

    /**
     * @param array $params
     * @return OAuthToken
     * @throws \yii\authclient\InvalidResponseException
     * @inheritdoc
     */
    public function authenticate(array $params = []): OAuthToken
    {
        if (empty($params)) {
            $params = [
                'email' => $this->username,
                'password' => $this->password,
            ];
        }
        return parent::authenticate($params);
    }

    #endregion BaseOAuth
}