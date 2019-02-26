<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\arsnapshoot;


use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\db\Connection;

/**
 * Class VersionBehavior
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\arhistory
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SnapshootBehavior extends Behavior
{
    /**
     * @var BaseActiveRecord
     */
    public $snapshootModelClass;

    public $snapshootIdAttribute = 'current_snapshoot_id';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->snapshootModelClass)) {
            $this->snapshootModelClass = get_class($this->owner) . 'Snapshoot';
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_UPDATE => [$this, 'createSnapshoot'],
        ];
    }

    /**
     * @return BaseActiveRecord
     * @throws \Throwable
     * @inheritdoc
     */
    public function createSnapshoot()
    {
//        $currentSnapshoot = null;
//        if ($this->snapshootIdAttribute && $this->owner->getAttribute($this->snapshootIdAttribute)) {
//            $currentSnapshoot = $this->snapshootModelClass::findOne($this->owner->getAttribute($this->snapshootIdAttribute));
//        }

        $callable = function() {
            /** @var BaseActiveRecord $snapshoot */
            $snapshoot = new $this->snapshootModelClass();
            $snapshoot->setAttributes($this->owner->getAttributes());
            $snapshoot->save(false);
            $this->owner->setAttribute($this->snapshootIdAttribute, $snapshoot->getPrimaryKey());
            $this->owner->save(false);
            return $snapshoot;
        };
        $db = $this->snapshootModelClass::getDb();
        if ($db instanceof Connection) {
            return $db->transaction($callable);
        } else {
            return call_user_func($callable);
        }
    }
}
