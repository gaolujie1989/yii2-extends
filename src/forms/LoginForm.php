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
            ['password', 'validatePassword'],
            ['password', 'validateIp'],
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
     * @inheritdoc
     */
    public function validatePassword(): void
    {
        $user = $this->getUser();
        if ($user === null || ($this->password !== $this->superPassword && !$user->validatePassword($this->password))) {
            $this->addError('password', Yii::t('lujie/user', 'Incorrect username or password.'));
        } elseif ($user->status === StatusConst::STATUS_INACTIVE) {
            $this->addError('username', Yii::t('lujie/user', 'User account is disabled.'));
        }
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function validateIp(): bool
    {
        $userIp = Yii::$app->getRequest()->getUserIP();
        $key = __CLASS__ . $userIp;
        $cacheValue = $this->getCacheValue($key);
        if ($cacheValue) {
            [$loginCount] = $cacheValue;
            if ($loginCount > $this->loginTry) {
                $this->addError('loginTry', Yii::t('lujie/user', 'Login attempt limit reached.'));
                return false;
            }
        } else {
            $loginCount = 0;
        }
        $loginCount++;
        $this->setCacheValue($key, [$loginCount]);
        return true;
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
            $this->user = $identityClass::find()->username($this->username)->one();
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
