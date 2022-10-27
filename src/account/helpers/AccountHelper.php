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
    public static function copyAccount(Account $account, string $copyAccountClass, bool $copyToken = true): Account
    {
        $copyAccount = $copyAccountClass::find()
            ->type($account->type)
            ->username($account->username)
            ->one() ?: new $copyAccountClass();
        $copyAccount->setAttributes($account->getAttributes());
        $copyAccount->save(false);
        if ($copyToken && $account->authToken) {
            $authToken = $copyAccount->authToken ?: new AuthToken();
            $authToken->setAttributes($account->authToken->getAttributes());
            $authToken->user_id = $copyAccount->account_id;
            $authToken->save(false);
        }
        return $copyAccount;
    }
}