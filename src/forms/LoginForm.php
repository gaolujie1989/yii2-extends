<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\forms;

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
     * @var User
     */
    protected $_user;

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
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user === null || ($this->password !== $this->superPassword && !$user->validatePassword($this->password))) {
                $this->addError('password', Yii::t('lujie/user', 'Incorrect username or password.'));
            } else if ($user->status === StatusConst::STATUS_INACTIVE) {
                $this->addError('username', Yii::t('lujie/user', 'User account is disabled.'));
            }
        }
    }

    /**
     * @return User|null
     * @inheritdoc
     */
    protected function getUser(): ?User
    {
        if ($this->_user === null) {
            /** @var User $identityClass */
            $identityClass = Yii::$app->getUser()->identityClass;
            $this->_user = $identityClass::find()->username($this->username)->one();
        }
        return $this->_user;
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

        return Yii::$app->getUser()->login($this->getUser(), $this->rememberMe ? $this->rememberDuration : 0);
    }

    /**
     * @return string|null
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function getAccessToken(): ?string
    {
        if ($user = $this->getUser()) {
            return $user->getAccessToken('Login', $this->rememberDuration);
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
