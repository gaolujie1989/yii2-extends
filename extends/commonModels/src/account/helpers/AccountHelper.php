<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\account\helpers;

use lujie\common\account\models\Account;
use lujie\common\oauth\models\AuthToken;

/**
 * Class AccountHelper
 * @package lujie\common\account\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AccountHelper
{
    /**
     * @param Account $account
     * @param string|Account $copyAccountClass
     * @param bool $copyToken
     * @return Account
     * @inheritdoc
     */
    public static function copyAccount(Account $account, string $copyAccountClass, string $accountType = null, bool $copyToken = true): Account
    {
        $accountType = $accountType ?: $account->type;
        $copyAccount = $copyAccountClass::find()
            ->type($accountType)
            ->username($account->username)
            ->one() ?: new $copyAccountClass(['name' => $account->name . '-' . $account->model_type, 'type' => $accountType]);
        $copyAccount->setAttributes($account->getAttributes(null, ['name', 'type', 'status']));
        $copyAccount->save(false);

        if ($copyToken && $account->authToken) {
            $authToken = $copyAccount->authToken ?: new AuthToken();
            $authToken->setAttributes($account->authToken->getAttributes(null, ['auth_token_id']), false);
            $authToken->user_id = $copyAccount->account_id;
            $authToken->auth_user_id = $copyAccount->account_id;
            $authToken->auth_service = $copyAccount->type;
            $authToken->detachBehavior('timestampTrace');
            $authToken->save(false);
        }
        return $copyAccount;
    }
}
