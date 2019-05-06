<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload;


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

    public $attribute = 'file';

    public $path = '@uploads';

    public $url = 'staticUrl';

    public $unlinkOnUpdate = false;

    public $unlinkOnDelete = true;

    /**
     * @var Filesystem
     */
    public $fs = 'filesystem';

    public $suffixes = ['Url', 'Path', 'Content'];

    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
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
    public function events()
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
    public function afterUpdate(AfterSaveEvent $event)
    {
        if ($this->unlinkOnUpdate && isset($event->changedAttributes[$this->attribute])) {
            $oldFileName = $event->changedAttributes[$this->attribute];
            $this->deleteFile($oldFileName);
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
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
    public function getUrl()
    {
        $value = $this->owner->{$this->attribute};
        return $this->url . $value;
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getPath()
    {
        $value = $this->owner->{$this->attribute};
        return $this->path . $value;
    }

    /**
     * @return bool|false|string
     * @inheritdoc
     */
    public function getContent()
    {
        $value = $this->owner->{$this->attribute};
        return $this->loadFile($value);
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        $canGetProperty = parent::canGetProperty($name, $checkVars);
        if ($canGetProperty) {
            return true;
        }

        if ($this->isUploadFileAttribute($name)) {
            return true;
        }
        return $canGetProperty;
    }

    /**
     * @param $name
     * @return bool
     * @inheritdoc
     */
    protected function isUploadFileAttribute($name)
    {
        foreach ($this->suffixes as $suffix) {
            if (ucfirst(substr($name, -strlen($suffix))) == $suffix) {
                $attribute = substr($name, 0, strlen($name) - strlen($suffix));
                return $attribute == $this->attribute;
            }
        }

        return false;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __get($name)
    {
        foreach ($this->suffixes as $suffix) {
            if (substr($name, -strlen($suffix)) == $suffix) {
                $attribute = substr($name, 0, strlen($name) - strlen($suffix));
                if ($attribute == $this->attribute) {
                    $getter = 'get' . $suffix;
                    return $this->$getter();
                }
            }
        }

        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param array $params
     * @return mixed
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (strpos($name, 'get') === 0) {
            foreach ($this->suffixes as $suffix) {
                if (substr($name, -strlen($suffix)) == $suffix) {
                    $attribute = lcfirst(substr($name, 3, strlen($name) - strlen($suffix) - 3));
                    if ($attribute == $this->attribute) {
                        $getter = 'get' . $suffix;
                        return $this->$getter();
                    }
                }
            }
        }

        return parent::__call($name, $params);
    }

    #endregion
}
