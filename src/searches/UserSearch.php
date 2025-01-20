<?php

namespace lujie\user\searches;

use lujie\extend\db\SearchTrait;
use lujie\extend\helpers\QueryHelper;
use lujie\user\models\User;
use yii\db\ActiveQueryInterface;

/**
 * Class UserSearch
 * @package lujie\user\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UserSearch extends User
{
    use SearchTrait;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'email'], 'safe']
        ];
    }

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = $this->searchQuery();
        QueryHelper::filterValue($query, $this->getAttributes(['username', 'email']), true);
        return $query;
    }
}
