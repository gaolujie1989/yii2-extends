<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\center\forms;

use yii\base\Model;

/**
 * Class ResetPasswordForm
 * @package lujie\user\center\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ResetPasswordForm extends Model
{
    public $email;

    public $password;

    public $verifyCode;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['email', 'password', 'verifyCode'], 'required']
        ];
    }

    public function sendVerifyCode()
    {

    }
}
