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
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['oldPassword', 'newPassword'], 'required'],
            [['oldPassword'], 'validatePassword'],
            ['newPassword', 'string', 'min' => 6],
        ];
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function validatePassword(): void
    {
        if (!$this->hasErrors()) {
            /** @var User $user */
            $user = Yii::$app->getUser()->getIdentity();
            if (!$user || !$user->validatePassword($this->oldPassword)) {
                $this->addError('password', Yii::t('lujie/user', 'Incorrect old password.'));
            }
        }
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
