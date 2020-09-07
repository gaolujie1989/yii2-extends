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
     * @return array
     * @inheritdoc
     */
    public function relations(): array
    {
        return [
            'relationSaveFiles' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['modelFiles'],
                'indexKeys' => [
                    'modelFiles' => 'file',
                ],
                'linkUnlinkRelations' => ['modelFiles']
            ],
            'relationDeleteFiles' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['modelFiles'],
            ]
        ];
    }

    #region mock file relation query method

    /**
     * @param string $name
     * @return bool
     * @inheritdoc
     */
    public function hasMethod($name)
    {
        if (strpos($name, 'get') === 0 && isset($this->modelFileTypes[lcfirst(substr($name, 3))])) {
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
        if (isset($this->modelFileTypes[lcfirst($name)])) {
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     * @return ActiveQuery|UploadModelFileQuery
     * @inheritdoc
     */
    public function getUploadModelFiles(string $name): ActiveQuery
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
     * @inheritdoc
     */
    public function getFiles(): array
    {
        $files = [];
        $modelFiles = ArrayHelper::index($this->owner->modelFiles, null, 'model_type');
        foreach ($this->modelFileTypes as $relationKey => $modelFileType) {
            $files[$relationKey] = $modelFiles[$modelFileType] ?? [];
        }
        return $files;
    }

    /**
     * @param array $files
     * @throws \yii\base\InvalidConfigException
     */
    public function setFiles(array $files): void
    {
        $modelFiles = [];
        foreach ($files as $relationKey => $modelFiles) {
            foreach ($modelFiles as $modelFile) {
                $modelFile['model_type'] = $this->modelFileTypes[$relationKey];
                $modelFiles[] = $modelFile;
            }
        }

        /** @var RelationSavableBehavior $behavior */
        $behavior = $this->owner->getBehavior('relationSave');
        $behavior->setRelation('modelFiles', $modelFiles);
    }

    #endregion
}