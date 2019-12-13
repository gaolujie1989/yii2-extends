<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use Yii;
use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\web\User;

/**
 * Class TraceableBootstrap
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordTracer extends BaseObject implements BootstrapInterface
{
    public $createdAtAttribute = 'created_at';
    public $createdByAttribute = 'created_by';
    public $updatedAtAttribute = 'updated_at';
    public $updatedByAttribute = 'updated_by';

    /**
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_BEFORE_INSERT, [$this, 'beforeInsert']);
        Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_BEFORE_UPDATE, [$this, 'beforeUpdate']);
    }

    /**
     * @param Event $event
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function beforeInsert(Event $event)
    {
        /** @var BaseActiveRecord $model */
        $model = $event->sender;
        if ($model->hasAttribute($this->createdAtAttribute)) {
            $model->setAttribute($this->createdAtAttribute, time());
        }
        if ($model->hasAttribute($this->createdByAttribute)) {
            $model->setAttribute($this->createdByAttribute, $this->getActionBy());
        }
    }

    /**
     * @param Event $event
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function beforeUpdate(Event $event)
    {
        /** @var BaseActiveRecord $model */
        $model = $event->sender;
        if ($model->hasAttribute($this->updatedAtAttribute)) {
            $model->setAttribute($this->updatedAtAttribute, time());
        }
        if ($model->hasAttribute($this->updatedByAttribute)) {
            $model->setAttribute($this->updatedByAttribute, $this->getActionBy());
        }
    }

    /**
     * @return int
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getActionBy(): int
    {
        /** @var User $user */
        $user = Yii::$app->get('user', false);
        if ($user === null || $user->getIsGuest()) {
            return 0;
        }
        return $user->getId();
    }
}
