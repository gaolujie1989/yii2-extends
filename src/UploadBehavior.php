<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload;

use creocoder\flysystem\Filesystem;
use yii\base\Behavior;
use yii\base\Model;
use yii\base\ModelEvent;
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
    public function init(): void
    {
        parent::init();
        $this->initFsAndPath();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
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
    public function beforeValidate(): void
    {
        /** @var Model $model */
        $model = $this->owner;
        if (!$this->isInScenarios($model)) {
            return;
        }

        $file = $model->{$this->attribute};
        if ($file instanceof UploadedFile) {
            $this->_uploadedFile = $file;
        } else if ($this->inputName) {
            $this->_uploadedFile = UploadedFile::getInstanceByName($this->inputName);
        } else {
            $this->_uploadedFile = UploadedFile::getInstance($model, $this->attribute);
        }
        if ($this->_uploadedFile instanceof UploadedFile) {
            $model->{$this->attribute} = $this->_uploadedFile;
        }
    }

    /**
     * @param ModelEvent $event
     * @throws \yii\base\Exception
     * @throws \Exception
     * @inheritdoc
     */
    public function beforeSave(ModelEvent $event): void
    {
        /** @var Model $model */
        $model = $this->owner;
        if (!$this->isInScenarios($model)) {
            return;
        }

        if ($this->_uploadedFile instanceof UploadedFile) {
            $fileName = $this->getFileName($this->_uploadedFile);
            if ($this->saveUploadedFile($fileName, $this->_uploadedFile, $this->deleteTempFile)) {
                $model->{$this->attribute} = $fileName;
            } else {
                $event->isValid = false;
                $model->addError($this->attribute, 'Save uploaded file failed.');
            }
        }
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterUpdate(AfterSaveEvent $event): void
    {
        if ($this->deleteOnUpdate && isset($event->changedAttributes[$this->attribute])
            && $oldFileName = $event->changedAttributes[$this->attribute]) {
            $this->deleteFile($oldFileName);
        }
    }

    /**
     * @param Model $model
     * @return bool
     * @inheritdoc
     */
    protected function isInScenarios(Model $model): bool
    {
        return empty($this->scenarios) || in_array($model->getScenario(), $this->scenarios, true);
    }

    /**
     * @param UploadedFile $file
     * @return string
     * @throws \Exception
     * @inheritdoc
     */
    protected function getFileName(UploadedFile $file): string
    {
        if ($this->generateNewName) {
            return is_callable($this->generateNewName)
                ? call_user_func($this->generateNewName, $file)
                : $this->generateFileName($file->extension);
        }
        return $file->name;
    }

    /**
     * @param $suffix
     * @return string
     * @throws \Exception
     * @inheritdoc
     */
    protected function generateFileName($suffix): string
    {
        $pairs = [
            '{date}' => date('ymd'),
            '{datetime}' => date('ymdHis'),
            '{rand}' => random_int(1000, 9999),
            '{ext}' => $suffix
        ];
        return strtr($this->newNameTemplate, $pairs);
    }
}
