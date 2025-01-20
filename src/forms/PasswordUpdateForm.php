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
class PasswordUpdateForm extends Model
{
    public $oldPassword;
    public $newPassword;

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
        return [
            [['oldPassword', 'newPassword'], 'required'],
            [['oldPassword'], 'validateOldPassword'],
            [['newPassword'], 'string', 'min' => 8],
            [['newPassword'], 'match', 'pattern' => '/[0-9]+/', 'message' => 'New password needs number.'],
            [['newPassword'], 'match', 'pattern' => '/[a-z]+/', 'message' => 'New password needs lowercase letters.'],
            [['newPassword'], 'match', 'pattern' => '/[A-Z]+/', 'message' => 'New password needs uppercase letters.'],
            [['newPassword'], 'match', 'pattern' => '/[~!@#$%^&*()_+`,.]+/', 'message' => 'New password needs special characters.'],
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
        if ($this->user === null) {
            /** @var User $identityClass */
            $identityClass = Yii::$app->getUser()->identityClass;
            $this->user = $identityClass::findOne(Yii::$app->getUser()->getId());
        }
        return $this->user;
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function update(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $user = Yii::$app->getUser()->getIdentity();
        $user->setPassword($this->newPassword);
        return $user->save(false);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return [];
    }
}
