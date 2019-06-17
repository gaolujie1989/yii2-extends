<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\history;


use lujie\ar\history\models\History;
use yii\base\Behavior;
use yii\db\ActiveRecord as DbActiveRecord;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;
use yii\db\Exception;
use yii\mongodb\ActiveRecord as MongodbActiveRecord;
use yii\redis\ActiveRecord as RedisActiveRecord;

/**
 * Class HistoryBehaviors
 *
 * only save update history detail, for delete, only log delete message
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class HistoryBehavior extends Behavior
{
    /**
     * @var string
     */
    public $historyModelClass = History::class;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'saveHistory'
        ];
    }

    /**
     * @param AfterSaveEvent $event
     * @throws Exception
     * @inheritdoc
     */
    public function saveModelHistory(AfterSaveEvent $event): void
    {
        if (empty($event->changedAttributes)) {
            return;
        }

        $owner = $this->owner;
        /** @var History $history */
        $history = new $this->historyModelClass();
        $history->setAttributes([
            'table_name' => $this->getTableName($owner),
            'row_id' => $owner->getPrimaryKey(),
            'old_data' => $event->changedAttributes,
            'new_data' => $owner->getAttributes(array_keys($event->changedAttributes)),
        ]);
        if (!$history->save(false)) {
            throw new Exception('Save Model History Failed.');
        }
    }

    /**
     * @param BaseActiveRecord $record
     * @return string
     * @inheritdoc
     */
    protected function getTableName(BaseActiveRecord $record): string
    {
        if ($record instanceof DbActiveRecord) {
            return $record::tableName();
        }
        if ($record instanceof MongodbActiveRecord) {
            return $record::collectionName();
        }
        if ($record instanceof RedisActiveRecord) {
            return $record::keyPrefix();
        }
        return '';
    }
}
