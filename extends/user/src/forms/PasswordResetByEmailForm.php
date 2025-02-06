<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\forms;

use lujie\user\models\User;
use Yii;
use yii\di\Instance;
use yii\mail\MailerInterface;

/**
 * Class ResetPasswordForm
 *
 * @property string $resetBy;
 *
 * @package lujie\user\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PasswordResetByEmailForm extends PasswordResetForm
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var MailerInterface
     */
    public $mailer = 'mailer';

    /**
     * @var User
     */
    protected $user;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['email'], 'required'],
            [['email'], 'validateEmail'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function validateEmail(): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError('password', Yii::t('lujie/user', 'Email not exists.'));
            }
        }
    }

    /**
     * @return User|null
     * @inheritdoc
     */
    protected function getUser(): ?User
    {
        if ($this->user === null) {
            /** @var User $identityClass */
            $identityClass = Yii::$app->getUser()->identityClass;
            $this->user = $identityClass::find()->email($this->email)->one();
        }
        return $this->user;
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function sendVerifyCode(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $this->mailer = Instance::ensure($this->mailer);
        return $this->mailer->compose()
            ->setSubject('Reset Password')
            ->setTextBody('Verification Code: ' . $this->generateVerifyCode())
            ->setTo($this->email)
            ->setFrom(Yii::$app->params['supportEmail'])
            ->send();
    }
}
