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
use yii\db\BaseActiveRecord;
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
        return [
            'relationSaveFiles' => [
                'class' => RelationSavableBehavior::class,
                'relations' => [$this->relationKey],
                'indexKeys' => [
                    $this->relationKey => 'file',
                ],
                'linkUnlinkRelations' => [$this->relationKey]
            ],
            'relationDeleteFiles' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => [$this->relationKey],
            ]
        ];
    }

    #region mock file relation query method

    public function __call($name, $params)
    {
        if (strpos($name, 'get') === 0 && isset($this->modelFileTypes[lcfirst(substr($name, 3))])) {
            return $this->getRelationUploadModelFiles(lcfirst(substr($name, 3)));
        }
        parent::__call($name, $params);
    }

    public function __get($name)
    {
        if (isset($this->modelFileTypes[lcfirst($name)])) {
            return $this->getRelationUploadModelFiles(lcfirst($name));
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @return bool
     * @inheritdoc
     */
    public function hasMethod($name)
    {
        if ($name === 'getModelFiles' || strpos($name, 'get') === 0 && isset($this->modelFileTypes[lcfirst(substr($name, 3))])) {
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
    public function canGetProperty($name, $checkVars = true)
    {
        if ($name === 'modelFiles' || isset($this->modelFileTypes[lcfirst($name)])) {
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
        /** @var BaseActiveRecord $owner */
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
        /** @var BaseActiveRecord $owner */
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