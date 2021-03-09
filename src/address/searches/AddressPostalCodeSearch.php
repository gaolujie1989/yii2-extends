<?php

namespace lujie\common\address\searches;

use lujie\common\address\models\AddressPostalCode;
use lujie\common\address\models\AddressPostalCodeQuery;
use lujie\extend\base\SearchTrait;
use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use yii\db\ActiveQueryInterface;

/**
 * Class AddressPostalCodeSearch
 * @package lujie\common\address\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AddressPostalCodeSearch extends AddressPostalCode
{
    use SearchTrait;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return array_merge(ModelHelper::searchRules($this), [
            [['country', 'note'], 'safe'],
        ]);
    }

    /**
     * @return ActiveQueryInterface|AddressPostalCodeQuery
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = ModelHelper::query($this);
        QueryHelper::filterValue($query, $this->getAttributes(['country']));
        QueryHelper::filterValue($query, $this->getAttributes(['note']), true);
        return $query;
    }
}
