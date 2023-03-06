<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\user\forms;

use lujie\extend\constants\StatusConst;
use lujie\user\models\UserApp;
use Yii;

/**
 * Class UserAppForm
 * @package lujie\user\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UserAppForm extends UserApp
{
    public $refreshSecret = false;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['user_id', 'name', 'status'], 'required'],
            [['user_id'], 'integer'],
            [['status'], 'in', 'range' => [
                StatusConst::STATUS_ACTIVE,
                StatusConst::STATUS_INACTIVE
            ]],
            [['refreshSecret'], 'safe']
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        if ($insert) {
            $this->key = Yii::$app->security->generateRandomString();
            $this->secret = Yii::$app->security->generateRandomString();
        } elseif ($this->refreshSecret) {
            $this->secret = Yii::$app->security->generateRandomString();
        }
        return parent::beforeSave($insert);
    }
}
