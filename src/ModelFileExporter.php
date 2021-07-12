<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;

use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;
use yii\db\BaseActiveRecord;

/**
 * Class ModelFileExporter
 * @package lujie\data\exchange
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelFileExporter extends FileExporter
{
    /**
     * @var BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var array
     */
    public $keyMap = [];

    /**
     * @var string
     */
    public $dataPreparer = 'prepareRows';

    /**
     * @var array
     */
    public $filterAttributes = [
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        $this->initTransformer();
        parent::init();
    }

    public function initTransformer(): void
    {
        if ($this->transformer) {
            return;
        }
        if (empty($this->keyMap)) {
            /** @var BaseActiveRecord $model */
            $model = new $this->modelClass();
            $attributes = array_keys($model->toArray());
            $attributes = array_diff($attributes, $model::primaryKey(), $this->filterAttributes);
            $this->keyMap = array_combine($attributes, $attributes);
        }
        $this->transformer = method_exists($this->modelClass, $this->dataPreparer)
            ? [
                'class' => ChainedTransformer::class,
                'transformers' => [
                    'prepareRows' => [$this->modelClass, $this->dataPreparer],
                    'keyMap' => [
                        'class' => KeyMapTransformer::class,
                        'unsetNotInMapKey' => true,
                        'keyMap' => $this->keyMap
                    ],
                ]
            ] : [
                'class' => ChainedTransformer::class,
                'transformers' => [
                    'keyMap' => [
                        'class' => KeyMapTransformer::class,
                        'keyMap' => $this->keyMap
                    ],
                ]
            ];
    }
}
