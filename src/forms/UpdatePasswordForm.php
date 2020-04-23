<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\forms;

use lujie\user\models\User;
use Yii;
use yii\base\Model;

/**
 * Class UpdatePasswordForm
 * @package lujie\user\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UpdatePasswordForm extends Model
{
    public $oldPassword;
    public $newPassword;

    /**
     * @var User
     */
    protected $_user;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['oldPassword', 'newPassword'], 'required'],
            [['oldPassword'], 'validateOldPassword'],
            ['newPassword', 'string', 'min' => 6],
        ];
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function validateOldPassword(): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user === null || !$user->validatePassword($this->oldPassword)) {
                $this->addError('oldPassword', Yii::t('lujie/user', 'Incorrect old password.'));
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
            $this->_user = $identityClass::findOne(Yii::$app->getUser()->getId());
        }
        return $this->_user;
    }

    /**
     * @return mixed
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function updatePassword()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $user = Yii::$app->getUser()->getIdentity();
        $user->setPassword($this->newPassword);
        return $user->save(false);
    }
}
