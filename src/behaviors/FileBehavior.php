<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\behaviors;

use creocoder\flysystem\Filesystem;
use Yii;
use yii\base\Behavior;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * Class FileBehavior
 * @package lujie\uploadImport\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileBehavior extends Behavior
{
    use FileTrait;

    /**
     * @var string
     */
    public $attribute = 'file';

    /**
     * @var string
     */
    public $url = 'staticUrl';

    public $unlinkOnUpdate = false;

    public $unlinkOnDelete = true;

    /**
     * @var string
     */
    public $path = '@uploads';

    /**
     * @var ?Filesystem
     */
    public $fs = 'filesystem';

    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->initFsAndPath();
        $this->url = Yii::$app->params[$this->url] ?? $this->url;
        $this->url = rtrim($this->url, '/') . '/';
    }

    #region delete old file on events

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterUpdate(AfterSaveEvent $event): void
    {
        if ($this->unlinkOnUpdate && isset($event->changedAttributes[$this->attribute])) {
            $oldFileName = $event->changedAttributes[$this->attribute];
            $this->deleteFile($oldFileName);
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete(): void
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        if ($this->unlinkOnDelete && $fileName = $model->getAttribute($this->attribute)) {
            $this->deleteFile($fileName);
        }
    }

    #endregion

    #region get file path url and content

    /**
     * @return string
     * @inheritdoc
     */
    public function getUrl(): string
    {
        $value = $this->owner->{$this->attribute};
        return $this->url . $value;
    }

    /**
     * @return string|null
     * @inheritdoc
     */
    public function getContent(): ?string
    {
        $value = $this->owner->{$this->attribute};
        return $this->read($value);
    }

    #endregion
}
