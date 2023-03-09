<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\oauth\forms;

use lujie\common\account\models\Account;
use lujie\common\oauth\models\AuthToken;
use lujie\extend\db\FormTrait;
use lujie\extend\helpers\ModelHelper;

/**
 * Class AuthTokenForm
 * @package lujie\common\oauth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthTokenForm extends AuthToken
{
    use FormTrait;

    /**
     * @var Account
     */
    public $accountClass;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = $this->formRules();
        $rules = ModelHelper::removeAttributesRules($rules, ['auth_service', 'auth_user_id', 'auth_username']);
        return array_merge($rules, [
            [['user_id'], 'linker',
                'targetClass' => $this->accountClass,
                'targetAttribute' => 'account_id',
                'linkAttributes' => [
                    'type' => 'auth_service',
                    'account_id' => 'auth_user_id',
                    'username' => 'auth_username',
                ]
            ],
            [['user_id', 'auth_service'], 'unique', 'targetAttribute' => ['user_id', 'auth_service']],
        ]);
    }
}