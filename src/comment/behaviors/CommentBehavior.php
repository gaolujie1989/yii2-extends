<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\comment\behaviors;

use yii\base\Behavior;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * Class CommentBehavior
 * @package lujie\common\comment\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CommentBehavior extends Behavior
{
    /**
     * @var string
     */
    public $relation = 'comments';

    /**
     * @var string
     */
    public $comment;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'saveComment',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'saveComment',
        ];
    }

    /**
     * @inheritdoc
     */
    public function saveComment(AfterSaveEvent $event): void
    {
        /** @var BaseActiveRecord $sender */
        $sender = $event->sender;
        $activeQuery = $sender->getRelation($this->relation);
        if ($activeQuery === null) {
            return;
        }
        if ($this->comment) {
            $comment = new $activeQuery->modelClass();
            $comment->model_id = $sender->primaryKey;
            $comment->content = $this->comment;
            $comment->save(false);
        }
    }
}