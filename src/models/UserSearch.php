<?php

namespace lujie\user\center\models;

/**
 * Class UserSearch
 * @package lujie\user\center\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UserSearch extends User
{

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'status'], 'safe']
        ];
    }

    /**
     * @return UserQuery
     * @inheritdoc
     */
    public function query(): UserQuery
    {
        return static::find()
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['LIKE', 'username', $this->username])
            ->andFilterWhere(['LIKE', 'username', $this->email]);
    }
}
