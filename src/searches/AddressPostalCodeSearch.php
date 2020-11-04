<?php

namespace lujie\common\address\searches;

use lujie\common\address\models\AddressPostalCode;
use lujie\common\address\models\AddressPostalCodeQuery;

/**
 * Class AddressPostalCodeSearch
 * @package lujie\common\address\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AddressPostalCodeSearch extends AddressPostalCode
{
    public $activeAt;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['country', 'postal_code', 'type'], 'safe'],
            [['activeAt'], 'date'],
        ];
    }

    /**
     * @return AddressPostalCodeQuery
     * @inheritdoc
     */
    public function query(): AddressPostalCodeQuery
    {
        $query = static::find()->andFilterWhere([
            'type' => $this->type,
            'status' => $this->status,
            'country' => $this->country,
        ]);
        $query->andFilterWhere(['LIKE', 'postal_code', $this->postal_code]);
        if ($this->activeAt) {
            $query->andFilterWhere(['<=', 'started_at', $this->activeAt])
                ->andFilterWhere(['>=', 'ended_at', $this->activeAt]);
        }
        return $query;
    }
}
