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
            [['password'], 'string', 'min' => 6]
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
