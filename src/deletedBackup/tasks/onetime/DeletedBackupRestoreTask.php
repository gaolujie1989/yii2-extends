<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\deleted\backup\tasks\onetime;

use lujie\common\deleted\backup\DeletedBackupManager;
use lujie\common\deleted\backup\models\DeletedBackup;
use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\scheduling\CronTask;
use yii\di\Instance;

/**
 * Class DeletedBackupRestoreTask
 * @package lujie\common\deleted\backup\tasks\onetime
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DeletedBackupRestoreTask extends CronTask implements ProgressInterface
{
    use ProgressTrait;

    public $deletedBackupIds = [];

    /**
     * @var DeletedBackupManager
     */
    public $deletedBackupManager = 'deletedBackupManager';

    public function getParams(): array
    {
        return array_merge(['deletedBackupIds'], parent::getParams());
    }

    /**
     * @return \Generator
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $this->deletedBackupManager = Instance::ensure($this->deletedBackupManager, DeletedBackupManager::class);
        $deletedBackupQuery = DeletedBackup::find()->deletedBackupId($this->deletedBackupIds);
        $progress = $this->getProgress($deletedBackupQuery->count());
        foreach ($deletedBackupQuery->each() as $deletedBackup) {
            $this->deletedBackupManager->restore($deletedBackup);
            yield ++$progress->done;
        }
        yield;
    }
}
