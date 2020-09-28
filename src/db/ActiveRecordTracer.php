<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use lujie\extend\helpers\IdentityHelper;
use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\BaseActiveRecord;

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

    public $saveEmptyOnUpdateBy = true;

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
        $this->appendCreatedAttributes($model);
        $this->appendUpdatedAttributes($model);
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
        $this->appendUpdatedAttributes($model);
    }

    /**
     * @param BaseActiveRecord $model
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function appendCreatedAttributes(BaseActiveRecord $model): void
    {
        if ($model->hasAttribute($this->createdAtAttribute)) {
            $model->setAttribute($this->createdAtAttribute, time());
        }
        if ($model->hasAttribute($this->createdByAttribute)) {
            $model->setAttribute($this->createdByAttribute, IdentityHelper::getId());
        }
    }

    /**
     * @param BaseActiveRecord $model
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function appendUpdatedAttributes(BaseActiveRecord $model): void
    {
        if ($model->hasAttribute($this->updatedAtAttribute)) {
            $model->setAttribute($this->updatedAtAttribute, time());
        }
        if ($model->hasAttribute($this->updatedByAttribute)) {
            if (($value = IdentityHelper::getId()) || $this->saveEmptyOnUpdateBy) {
                $model->setAttribute($this->updatedByAttribute, $value);
            }
        }
    }
}
