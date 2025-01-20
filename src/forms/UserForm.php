<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\forms;

use lujie\user\models\User;

/**
 * Class UserForm
 * @package lujie\user\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UserForm extends User
{
    public $password;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['password'], 'string', 'min' => 8],
            [['password'], 'match', 'pattern' => '/[0-9]+/', 'message' => 'New password needs number.'],
            [['password'], 'match', 'pattern' => '/[a-z]+/', 'message' => 'New password needs lowercase letters.'],
            [['password'], 'match', 'pattern' => '/[A-Z]+/', 'message' => 'New password needs uppercase letters.'],
            [['password'], 'match', 'pattern' => '/[~!@#$%^&*()_+{}|:"<>?`-=[]\;\',./]/', 'message' => 'New password needs special characters.'],
        ]);
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        if ($this->password) {
            $this->setPassword($this->password);
        }
        return parent::beforeSave($insert);
    }
}
