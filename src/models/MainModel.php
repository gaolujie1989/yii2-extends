<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\eav\models;

use lujie\alias\behaviors\AliasPropertyBehavior;
use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\extend\helpers\ModelRuleHelper;
use lujie\upload\models\UploadModelFile;
use lujie\upload\models\UploadModelFileQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class MainModel
 *
 * @property ModelText[] $modelTexts
 * @property UploadModelFile[] $modelFiles
 *
 * @package lujie\eav\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class MainModel extends ActiveRecord
{
    public const MODEL_TYPE = 'DEFAULT';

    public const KEYS = ['XXX'];

    public const CHANNELS = ['XXX'];

    public const NOUPDATE_VALUE = 'NOUPDATE';

    public const FILE_TYPES = ['XXX'];

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['texts', 'files'], 'safe'],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['modelTexts', 'modelFiles'],
                'indexKeys' => [
                    'modelTexts' => static function ($text) {
                        return $text['key'] . '-' . $text['channel'];
                    },
                    'modelFiles' => 'file',
                ],
                'linkUnlinkRelations' => ['modelFiles']
            ],
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['modelTexts', 'modelFiles'],
            ]
        ]);
    }
    
    #region texts

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getModelTexts(): ModelTextQuery
    {
        $primaryKey = static::primaryKey();
        return $this->hasMany(ModelText::class, ['model_id' => $primaryKey[0]])->onCondition(['model_type' => static::MODEL_TYPE]);
    }
    
    /**
     * @return array
     * @inheritdoc
     */
    public function getTexts(): array
    {
        $texts = ArrayHelper::map($this->modelTexts, 'key', 'value', 'channel');
        if (static::KEYS && static::CHANNELS) {
            $default = array_fill_keys(static::CHANNELS, array_fill_keys(static::KEYS, ''));
            $texts = ArrayHelper::merge($default, $texts);
        }
        return $texts;
    }

    /**
     * @param array $texts
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function setTexts(array $texts): void
    {
        $modelTexts = [];
        foreach (static::CHANNELS as $channel) {
            if (empty($texts[$channel])) {
                continue;
            }
            $textValues = $texts[$channel];
            foreach (static::KEYS as $key) {
                if (is_array($textValues[$key])) {
                    continue;
                }
                $modelTexts[] = ($textValues[$key] === static::NOUPDATE_VALUE) ? [
                    'key' => $key,
                    'channel' => $channel,
                ] : [
                    'key' => $key,
                    'text' => $textValues[$key],
                    'channel' => $channel,
                ];
            }
        }

        /** @var RelationSavableBehavior $behavior */
        $behavior = $this->getBehavior('relationSave');
        $behavior->setRelation('modelTexts', $modelTexts);
    }

    #endregion

    #region files

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getModelFiles(): UploadModelFileQuery
    {
        $primaryKey = static::primaryKey();
        return $this->hasMany(UploadModelFile::class, ['model_id' => $primaryKey[0]])->onCondition(['model_type' => static::FILE_TYPES]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getFiles(): array
    {
        return ArrayHelper::index($this->modelFiles, null, 'model_type');
    }

    /**
     * @param array $texts
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function setFiles(array $files): void
    {
        $modelFiles = array_merge(...array_values($files));

        /** @var RelationSavableBehavior $behavior */
        $behavior = $this->getBehavior('relationSave');
        $behavior->setRelation('modelFiles', $modelFiles);
    }

    #endregion
}