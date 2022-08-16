<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\comment\behaviors;

use yii\base\Behavior;
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
    public $relation = 'comment';

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
    public function saveComment(): void
    {
        /** @var BaseActiveRecord $owner */
        $owner = $this->owner;
        $activeQuery = $owner->getRelation($this->relation, false);
        if ($activeQuery === null) {
            return;
        }
        if ($this->comment) {
            $comment = new $activeQuery->modelClass();
            $comment->model_id = $owner->primaryKey[0];
            $comment->content = $this->comment;
            $comment->save(false);
        }
    }
}