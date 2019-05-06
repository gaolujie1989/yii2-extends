<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload;

use creocoder\flysystem\Filesystem;
use yii\base\Behavior;
use yii\base\Model;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;
use yii\web\UploadedFile;

/**
 * Class UploadBehavior
 * @package lujie\uploadImport\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadBehavior extends Behavior
{
    use FileTrait;

    public $attribute = 'file';

    public $scenarios = [];

    public $inputName;

    /**
     * not use /tmp, file may be clean by system
     * @var string
     */
    public $path = '@uploads';

    /**
     * @var Filesystem
     */
    public $fs = 'filesystem';

    public $generateNewName = true;

    public $newNameTemplate = '{datetime}_{rand}.{ext}';

    public $deleteOnUpdate = true;

    public $deleteTempFile = true;

    private $_uploadedFile;

    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initFsAndPath();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function events()
    {
        $events = [
            Model::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
        if ($this->owner instanceof BaseActiveRecord) {
            $events[BaseActiveRecord::EVENT_BEFORE_INSERT] = 'beforeSave';
            $events[BaseActiveRecord::EVENT_BEFORE_UPDATE] = 'beforeSave';
            $events[BaseActiveRecord::EVENT_AFTER_UPDATE] = 'afterUpdate';
        }
        return $events;
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        /** @var Model $model */
        $model = $this->owner;
        if (!$this->isInScenarios($model)) {
            return;
        }

        $file = $model->{$this->attribute};
        if ($file instanceof UploadedFile) {
            $this->_uploadedFile = $file;
        } else {
            if ($this->inputName) {
                $this->_uploadedFile = UploadedFile::getInstanceByName($this->inputName);
            } else {
                $this->_uploadedFile = UploadedFile::getInstance($model, $this->attribute);
            }
        }
        if ($this->_uploadedFile instanceof UploadedFile) {
            $model->{$this->attribute} = $this->_uploadedFile;
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        /** @var Model $model */
        $model = $this->owner;
        if (!$this->isInScenarios($model)) {
            return;
        }

        if ($this->_uploadedFile instanceof UploadedFile) {
            $fileName = $this->getFileName($this->_uploadedFile);
            $this->saveUploadedFile($fileName, $this->_uploadedFile, $this->deleteTempFile);
            $model->{$this->attribute} = $fileName;
        }
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterUpdate(AfterSaveEvent $event)
    {
        if ($this->deleteOnUpdate && isset($event->changedAttributes[$this->attribute])) {
            if ($oldFileName = $event->changedAttributes[$this->attribute]) {
                $this->deleteFile($oldFileName);
            }
        }
    }

    /**
     * @param Model $model
     * @return bool
     * @inheritdoc
     */
    protected function isInScenarios(Model $model): bool
    {
        return empty($this->scenarios) || in_array($model->getScenario(), $this->scenarios);
    }

    /**
     * @param UploadedFile $file
     * @return mixed|string
     * @inheritdoc
     */
    protected function getFileName(UploadedFile $file)
    {
        if ($this->generateNewName) {
            return is_callable($this->generateNewName)
                ? call_user_func($this->generateNewName, $file)
                : $this->generateFileName($file->extension);
        } else {
            return $file->name;
        }
    }

    /**
     * @param $suffix
     * @return string
     * @inheritdoc
     */
    protected function generateFileName($suffix)
    {
        $pairs = [
            '{date}' => date('ymd'),
            '{datetime}' => date('ymdHis'),
            '{rand}' => rand(1000, 9999),
            '{ext}' => $suffix
        ];
        return strtr($this->newNameTemplate, $pairs);
    }
}
