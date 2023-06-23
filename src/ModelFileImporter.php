<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;

use lujie\data\exchange\pipelines\ActiveRecordPipeline;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FillDefaultValueTransformer;
use lujie\data\exchange\transformers\FilterTransformer;
use lujie\data\exchange\transformers\KeyMapTransformer;
use lujie\extend\helpers\ExcelHelper;
use lujie\extend\helpers\TemplateHelper;
use Yii;
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
     * @var bool
     */
    public $keyMapFlip = true;

    /**
     * @var string
     */
    public $filterKey = '';

    /**
     * @var array
     */
    public $keyMapNotes = [];

    /**
     * @var array
     */
    public $defaultValues = [];

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
                    'keyMap' => $this->keyMap,
                    'keyMapFlip' => $this->keyMapFlip,
                ],
                'filter' => [
                    'class' => FilterTransformer::class,
                    'filterKey' => $this->filterKey
                ],
            ]
        ];
        if ($this->defaultValues) {
            $this->transformer['transformers']['default'] = [
                'class' => FillDefaultValueTransformer::class,
                'defaultValues' => $this->defaultValues
            ];
        }
        if ($this->customTransformer) {
            $this->transformer['transformers']['custom'] = $this->customTransformer;
        }
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

    /**
     * @param string $filePathTemplate
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Exception
     * @inheritdoc
     */
    public function templateFile(string $filePathTemplate = '@runtime/imports/templates/{datetime}_{rand}.xlsx'): string
    {
        $path = Yii::getAlias(TemplateHelper::generate($filePathTemplate));
        $columns = array_keys($this->keyMap);
        $data = [$columns];
        if ($this->keyMapNotes) {
            $data[] = array_merge(array_fill_keys($columns, ''), $this->keyMapNotes);
        }
        ExcelHelper::writeExcel($path, $data, false);
        return $path;
    }
}
