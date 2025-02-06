<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\forms;

use lujie\extend\caching\CachingTrait;
use lujie\user\models\User;
use yii\base\Model;

/**
 * Class ResetPasswordForm
 *
 * @property string $resetBy;
 *
 * @package lujie\user\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class PasswordResetForm extends Model
{
    use CachingTrait;

    public const SCENARIO_SENDING_CODE = 'SENDING_CODE';
    public const SCENARIO_UPDATE_PASSWORD = 'RESET_PASSWORD';

    /**
     * @var string
     */
    public $verifyCode;

    /**
     * @var string
     */
    public $password;

    /**
     * @var int
     */
    public $verifyCodeDuration = 120;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['verifyCode', 'password'], 'required', 'on' => [static::SCENARIO_UPDATE_PASSWORD]],
            [['verifyCode'], 'string', 'min' => 6, 'max' => 6, 'on' => [static::SCENARIO_UPDATE_PASSWORD]],
            [['password'], 'string', 'min' => 6, 'on' => [static::SCENARIO_UPDATE_PASSWORD]],
            [['verifyCode'], 'validateVerifyCode', 'on' => [static::SCENARIO_UPDATE_PASSWORD]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function validateVerifyCode(): void
    {
        $verifyCode = $this->cache->get($this->getVerifyCodeCacheKey());
        if (empty($verifyCode) || $this->verifyCode !== $verifyCode) {
            $this->addError('verifyCode', 'Invalid verify code');
        }
    }

    /**
     * @return User
     * @inheritdoc
     */
    abstract protected function getUser(): ?User;

    /**
     * @return string
     * @inheritdoc
     */
    protected function getVerifyCodeCacheKey(): string
    {
        return 'VerifyCode:' . $this->getUser()->username;
    }

    /**
     * @return int
     * @throws \Exception
     * @inheritdoc
     */
    protected function generateVerifyCode(): int
    {
        $verifyCode = random_int(100000, 999999);
        $this->cache->set($this->getVerifyCodeCacheKey(), $verifyCode, $this->verifyCodeDuration);
        return $verifyCode;
    }

    abstract public function sendVerifyCode(): bool;

    /**
     * @return bool
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function resetPassword(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $user = $this->getUser();
        if ($user === null) {
            return false;
        }
        $user->setPassword($this->password);
        return $user->save(false);
    }
}
