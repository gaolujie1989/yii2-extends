<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentAccountQuery;

/**
 * Class FulfillmentAccountSearch
 * @package lujie\fulfillment\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentAccountSearch extends FulfillmentAccount
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['name', 'type', 'username'], 'safe'],
        ];
    }

    /**
     * @return FulfillmentAccountQuery
     * @inheritdoc
     */
    public function query(): FulfillmentAccountQuery
    {
        return static::find()
            ->andFilterWhere(['LIKE', 'name', $this->name])
            ->andFilterWhere([
                'type' => $this->type,
                'username', $this->username
            ]);
    }
}
