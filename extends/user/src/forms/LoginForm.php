<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\forms;

use lujie\extend\caching\CachingTrait;
use lujie\extend\constants\StatusConst;
use lujie\user\models\User;
use Yii;
use yii\base\Model;
use yii\caching\TagDependency;

/**
 * Class LoginForm
 * @package lujie\user\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class LoginForm extends Model
{
    use CachingTrait;

    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $password;

    /**
     * @var bool
     */
    public $rememberMe;

    /**
     * @var int
     */
    public $rememberDuration = 86400;

    /**
     * @var int
     */
    public $loginTry = 5;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    public $superPassword = '';

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validateIp'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'username' => Yii::t('lujie/user', 'Username'),
            'password' => Yii::t('lujie/user', 'Password'),
            'rememberMe' => Yii::t('lujie/user', 'Remember Me'),
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function validatePassword(): bool
    {
        $user = $this->getUser();
        if ($user === null || ($this->password !== $this->superPassword && !$user->validatePassword($this->password))) {
            $this->addError('password', Yii::t('lujie/user', 'Incorrect username or password.'));
            $this->logTryCount();
            return false;
        }
        if ($user->status === StatusConst::STATUS_INACTIVE) {
            $this->addError('username', Yii::t('lujie/user', 'User account is disabled.'));
            $this->logTryCount();
            return false;
        }
        return true;
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function validateIp(): bool
    {
        $userIp = Yii::$app->getRequest()->getUserIP();
        $key = __CLASS__ . $this->username . $userIp;
        $cacheValue = $this->getCacheValue($key);
        if ($cacheValue) {
            [$loginCount] = $cacheValue;
            if ($loginCount > $this->loginTry) {
                $this->addError('loginTry', Yii::t('lujie/user', 'Login attempt limit reached.'));
                return false;
            }
        }
        return true;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function logTryCount(): void
    {
        $userIp = Yii::$app->getRequest()->getUserIP();
        $key = __CLASS__ . $this->username . $userIp;
        $cacheValue = $this->getCacheValue($key);
        if ($cacheValue) {
            [$loginCount] = $cacheValue;
        } else {
            $loginCount = 0;
        }
        $loginCount++;
        $this->setCacheValue($key, [$loginCount], 3600, new TagDependency(['tags' => ['login']]));
    }

    /**
     * @return User|null
     * @inheritdoc
     */
    protected function getUser(): ?User
    {
        if ($this->user === null) {
            /** @var User $identityClass */
            $identityClass = Yii::$app->user->identityClass;
            if (str_contains($this->username, '@')) {
                $this->user = $identityClass::find()->email($this->username)->one();
            } else {
                $this->user = $identityClass::find()->username($this->username)->one();
            }
        }
        return $this->user;
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function login(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? $this->rememberDuration : 0);
    }

    /**
     * @return string|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getAccessToken(): ?string
    {
        if ($user = $this->getUser()) {
            return $user->createAccessToken('UserLogin', $this->rememberDuration);
        }
        return null;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return [
            'accessToken',
        ];
    }
}
