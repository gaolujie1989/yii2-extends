<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\deleted\backup\searches;

use lujie\common\deleted\backup\DeletedBackupManager;
use lujie\common\deleted\backup\models\DeletedBackup;
use yii\di\Instance;

/**
 * Class DeletedBackupRestoreForm
 * @package lujie\common\deleted\backup\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DeletedBackupRestoreForm extends DeletedBackup
{
    /**
     * @var DeletedBackupManager
     */
    public $deletedBackupManager = 'deletedBackupManager';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->deletedBackupManager = Instance::ensure($this->deletedBackupManager, DeletedBackupManager::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function restore(): bool
    {
        return $this->deletedBackupManager->restoreDeleted($this);
    }
}
