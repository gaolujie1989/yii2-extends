<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;

use lujie\data\exchange\pipelines\ActiveRecordPipeline;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FilterTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;
use yii\db\BaseActiveRecord;

/**
 * Class ModelFileImporter
 * @package lujie\data\exchange
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelFileImporter extends FileImporter
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
    public $filterKey = '';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        $this->initTransformer();
        $this->initPipeline();
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
            $safeAttributes = $model->safeAttributes();
            $this->keyMap = array_combine($safeAttributes, $safeAttributes);
        }
        if (empty($this->filterKey)) {
            $this->filterKey = reset($this->keyMap);
        }
        $this->transformer = [
            'class' => ChainedTransformer::class,
            'transformers' => [
                'keyMap' => [
                    'class' => KeyMapTransformer::class,
                    'keyMap' => $this->keyMap
                ],
                'filter' => [
                    'class' => FilterTransformer::class,
                    'filterKey' => $this->filterKey
                ],
            ]
        ];
    }

    public function initPipeline(): void
    {
        if ($this->pipeline) {
            return;
        }
        $this->pipeline = [
            'class' => ActiveRecordPipeline::class,
            'modelClass' => $this->modelClass,
            'runValidation' => true,
        ];
    }
}
