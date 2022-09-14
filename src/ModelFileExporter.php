<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;

use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;
use lujie\data\exchange\transformers\OptionTransformer;
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
     * @var array
     */
    public $options = [];

    /**
     * @var string
     */
    public $dataPreparer = 'prepareRows';

    /**
     * @var array
     */
    public $filterAttributes = [
        'id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    /**
     * @var array
     */
    public $customTransformer = [];

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
            $this->keyMap = $this->getModelAttributesKeyMap($model);
        }
        $transformers = [];
        if (method_exists($this->modelClass, $this->dataPreparer)) {
            $transformers['prepareRows'] = [$this->modelClass, $this->dataPreparer];
        }
        $transformers['options'] = [
            'class' => OptionTransformer::class,
            'options' => $this->options
        ];
        $transformers['keyMap'] = [
            'class' => KeyMapTransformer::class,
            'unsetNotInMapKey' => true,
            'keyMap' => $this->keyMap
        ];
        if ($this->customTransformer) {
            $transformers['custom'] = $this->customTransformer;
        }
        $this->transformer = [
            'class' => ChainedTransformer::class,
            'transformers' => $transformers
        ];
    }

    /**
     * @param BaseActiveRecord $model
     * @return array
     * @inheritdoc
     */
    public function getModelAttributesKeyMap(BaseActiveRecord $model): array
    {
        $model->setAttributes(array_fill_keys($model->attributes(), ''), false);
        $attributes = array_keys($model->fields());
        $attributes = array_diff($attributes, $model::primaryKey(), $this->filterAttributes);
        return array_combine($attributes, $attributes);
    }
}
