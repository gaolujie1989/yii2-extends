<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\searches;

use lujie\auth\models\AuthItem;
use lujie\extend\db\SearchTrait;
use yii\db\ActiveQueryInterface;

/**
 * Class AuthItemSearch
 * @package lujie\auth\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthItemSearch extends AuthItem
{
    use SearchTrait;

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $columns = array_diff($this->attributes(), ['data']);
        return $this->searchQuery()->select($columns);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        $fields = parent::fields();
        unset($fields['data']);
        return $fields;
    }
}