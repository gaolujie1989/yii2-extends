<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\forms;

use lujie\extend\constants\StatusConst;
use lujie\user\models\UserApp;
use Yii;
use yii\base\Model;

/**
 * Class AppLoginForm
 * @package lujie\user\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @deprecated
 */
class AppLoginForm extends Model
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $secret;

    /**
     * @var int
     */
    public $rememberDuration = 86400;

    /**
     * @var UserApp
     */
    protected $userApp;

    /**
     * @var UserApp
     */
    public $userAppClass = UserApp::class;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['key', 'secret'], 'required'],
            ['secret', 'validateSecret'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'key' => Yii::t('lujie/user', 'Key'),
            'secret' => Yii::t('lujie/user', 'Secret'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function validateSecret(): void
    {
        if (!$this->hasErrors()) {
            $userApp = $this->getUserApp();
            if ($userApp === null) {
                $this->addError('secret', Yii::t('lujie/userApp', 'Incorrect key or secret.'));
            } elseif ($userApp->status === StatusConst::STATUS_INACTIVE) {
                $this->addError('key', Yii::t('lujie/userApp', 'App account is disabled.'));
            } elseif ($userApp->user->status === StatusConst::STATUS_INACTIVE) {
                $this->addError('key', Yii::t('lujie/userApp', 'User account is disabled.'));
            }
        }
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
        return true;
    }

    /**
     * @return UserApp|null
     * @inheritdoc
     */
    protected function getUserApp(): ?UserApp
    {
        if ($this->userApp === null) {
            $this->userApp = $this->userAppClass::find()->key($this->key)->secret($this->secret)->one();
        }
        return $this->userApp;
    }

    /**
     * @return string|null
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function getAccessToken(): ?string
    {
        if ($userApp = $this->getUserApp()) {
            return $userApp->user->createAccessToken('AppLogin', $this->rememberDuration);
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
