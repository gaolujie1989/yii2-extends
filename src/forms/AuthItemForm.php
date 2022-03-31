<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use lujie\auth\models\AuthItem;
use lujie\extend\db\FormTrait;
use yii\di\Instance;
use yii\mongodb\rbac\MongoDbManager;
use yii\rbac\BaseManager;
use yii\rbac\DbManager;

/**
 * Class AuthItemForm
 * @package lujie\auth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthItemForm extends AuthItem
{
    use FormTrait;

    /**
     * @var BaseManager
     */
    public $authManager = 'authManager';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->authManager = Instance::ensure($this->authManager, BaseManager::class);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        $this->invalidateAuthCache();
    }

    /**
     * @inheritdoc
     */
    public function afterDelete(): void
    {
        parent::afterDelete();
        $this->invalidateAuthCache();
    }

    /**
     * @inheritdoc
     */
    protected function invalidateAuthCache(): void
    {
        if ($this->authManager instanceof DbManager || $this->authManager instanceof MongoDbManager) {
            $this->authManager->invalidateCache();
        }
    }
}