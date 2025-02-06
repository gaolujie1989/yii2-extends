<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\behaviors;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\upload\models\UploadModelFile;
use lujie\upload\models\UploadModelFileQuery;
use yii\base\Behavior;
use yii\base\InvalidArgumentException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class ModelFileBehavior
 * @package lujie\upload\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelFileBehavior extends Behavior
{
    /**
     * file type map, ['relationName' => 'FILE_TYPE'], ex. ['xxxImage' => 'MODEL_XXX_IMAGE', 'yyyFile' => 'MODEL_YYY_FILE']
     * @var array
     */
    public $modelFileTypes = [];

    /**
     * @var string
     */
    public $relationKey = 'modelFiles';

    /**
     * @var bool
     */
    public $attachRelationBehaviors = true;

    #region relation behaviors

    /**
     * @param \yii\base\Component $owner
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);
        if ($this->attachRelationBehaviors) {
            $owner->attachBehaviors($this->relationBehaviors());
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function relationBehaviors(): array
    {
        $relationKeys = array_merge([$this->relationKey], array_keys($this->modelFileTypes));
        $indexKeys = array_fill_keys($relationKeys, 'file');
        return [
            'relationSaveFiles' => [
                'class' => RelationSavableBehavior::class,
                'relations' => $relationKeys,
                'indexKeys' => $indexKeys,
                'linkUnlinkRelations' => $relationKeys,
            ],
            'relationDeleteFiles' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => $relationKeys,
            ]
        ];
    }

    #endregion

    #region mock file relation query method

    /**
     * @param string $name
     * @return bool
     */
    protected function isRelationMethod(string $name): bool
    {
        return strpos($name, 'get') === 0 && isset($this->modelFileTypes[lcfirst(substr($name, 3))]);
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function isRelationName(string $name): bool
    {
        return isset($this->modelFileTypes[lcfirst($name)]);
    }

    public function __call($name, $params)
    {
        if ($this->isRelationMethod($name)) {
            return $this->getRelationUploadModelFiles(lcfirst(substr($name, 3)));
        }
        parent::__call($name, $params);
    }

    public function __get($name)
    {
        if ($this->isRelationName($name)) {
            return $this->getRelationUploadModelFiles(lcfirst($name));
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @return bool
     * @inheritdoc
     */
    public function hasMethod($name): bool
    {
        if ($this->isRelationMethod($name)) {
            return true;
        }
        return parent::hasMethod($name);
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true): bool
    {
        if ($this->isRelationName($name)) {
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     * @return ActiveQuery|UploadModelFileQuery
     * @inheritdoc
     */
    public function getRelationUploadModelFiles(string $name): ActiveQuery
    {
        if (empty($this->modelFileTypes[$name])) {
            throw new InvalidArgumentException("Invalid model file {$name}");
        }
        /** @var ActiveRecord $owner */
        $owner = $this->owner;
        $primaryKey = $owner::primaryKey();
        return $owner->hasMany(UploadModelFile::class, ['model_id' => $primaryKey[0]])
            ->andOnCondition(['model_type' => $this->modelFileTypes[$name]]);
    }

    #endregion

    #region files attribute like model texts

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getModelFiles(): ActiveQuery
    {
        /** @var ActiveRecord $owner */
        $owner = $this->owner;
        $primaryKey = $owner::primaryKey();
        return $owner->hasMany(UploadModelFile::class, ['model_id' => $primaryKey[0]])
            ->andOnCondition(['model_type' => $this->modelFileTypes]);
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        $files = [];
        $modelFiles = ArrayHelper::index($this->owner->{$this->relationKey}, null, 'model_type');
        foreach ($this->modelFileTypes as $relationKey => $modelFileType) {
            $files[$relationKey] = $modelFiles[$modelFileType] ?? [];
        }
        return $files;
    }

    /**
     * @param array $files
     */
    public function setFiles(array $files): void
    {
        $modelFiles = [];
        foreach ($files as $relationKey => $relationFiles) {
            foreach ($relationFiles as $relationFile) {
                $relationFile['model_type'] = $this->modelFileTypes[$relationKey];
                $modelFiles[] = $relationFile;
            }
        }
        $this->owner->{$this->relationKey} = $modelFiles;
    }

    #endregion
}
