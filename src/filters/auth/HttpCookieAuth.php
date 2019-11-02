<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\filters\auth;

use yii\filters\auth\AuthMethod;
use yii\web\IdentityInterface;

/**
 * Class HttpCookieAuth
 * @package lujie\extend\filters\auth
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HttpCookieAuth extends AuthMethod
{
    /**
     * @var string the HTTP Cookie name
     */
    public $cookie = 'X-Api-Key';
    /**
     * @var string a pattern to use to extract the HTTP authentication value
     */
    public $pattern;

    /**
     * @param \yii\web\User $user
     * @param \yii\web\Request $request
     * @param \yii\web\Response $response
     * @return IdentityInterface|null
     * @throws \yii\web\UnauthorizedHttpException
     * @inheritdoc
     */
    public function authenticate($user, $request, $response): ?IdentityInterface
    {
        $authCookie = $request->getCookies()->get($this->cookie);

        if ($authCookie !== null) {
            $authCookie = $authCookie->value;
            if ($this->pattern !== null) {
                if (preg_match($this->pattern, $authCookie, $matches)) {
                    $authCookie = $matches[1];
                } else {
                    return null;
                }
            }

            $identity = $user->loginByAccessToken($authCookie, get_class($this));
            if ($identity === null) {
                $this->challenge($response);
                $this->handleFailure($response);
            }

            return $identity;
        }

        return null;
    }
}
