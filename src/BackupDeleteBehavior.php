<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\backupdelete;


use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * Class HistoryBehaviors
 *
 * backup deleted active records
 *
 * @property BaseActiveRecord|ActiveRecord $owner
 *
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BackupDeleteBehavior extends Behavior
{
    /**
     * @var string
     */
    public $backupModelClass = 'lujie\backupdelete\models\DeletedData';

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [BaseActiveRecord::EVENT_AFTER_DELETE => [$this, 'backupModel']];
    }

    /**
     * @param Event $event
     * @inheritdoc
     */
    public function backupModel(Event $event)
    {
        $owner = $this->owner;
        /** @var BaseActiveRecord $backupModel */
        $backupModel = new $this->backupModelClass();
        $backupModel->setAttributes([
            'table_name' => $owner instanceof ActiveRecord ? $owner::tableName() : '',
            'row_id' => $owner->getPrimaryKey(),
            'row_data' => $owner->getAttributes(),
        ]);
        $backupModel->save(false);
    }
}