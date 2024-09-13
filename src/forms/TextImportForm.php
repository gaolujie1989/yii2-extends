<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\forms;

use lujie\data\exchange\DataExchanger;
use lujie\data\exchange\FileImporter;
use lujie\data\exchange\sources\ArraySource;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FillDefaultValueTransformer;
use lujie\executing\Executor;
use lujie\extend\base\ModelAttributeTrait;
use lujie\extend\helpers\CsvHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\di\Instance;
use yii\helpers\Json;

/**
 * Class OwnerProductImportForm
 *
 * @property array $files
 *
 * @package ccship\common\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TextImportForm extends Model
{
    use ModelAttributeTrait;

    /**
     * @var string
     */
    public $textAttribute = 'content';

    /**
     * @var string
     */
    public $textAttributeLabel = 'Content';

    /**
     * @var string
     */
    public $textFormat = 'json';

    /**
     * @var array
     */
    public $dataAttributes = [];

    /**
     * @var DataExchanger
     */
    public $dataImporter;

    /**
     * @var Executor|string|null
     */
    public $executor = 'executor';

    /**
     * @var array import results
     */
    public $affectedRowCounts;

    /**
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->dataImporter = Instance::ensure($this->dataImporter, FileImporter::class);
        if ($this->executor && Yii::$app->has($this->executor)) {
            $this->executor = Instance::ensure($this->executor, Executor::class);
        } else {
            $this->executor = null;
        }
    }

    #region model overwrites

    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return array_merge([$this->textAttribute], $this->dataAttributes);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            $this->textAttribute => $this->textAttributeLabel
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = [
            [[$this->textAttribute], 'required'],
        ];
        if ($this->dataAttributes) {
            $rules[] = [$this->dataAttributes, 'required'];
        }
        return $rules;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(['affectedRowCounts'], $this->dataAttributes);
    }

    #endregion

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function import(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->textFormat === 'json') {
            $textData = Json::encode($this->{$this->textAttribute});
        } else if ($this->textFormat === 'csv') {
            $textData = CsvHelper::readCsv($this->{$this->textAttribute});
        } else {
            return false;
        }

        $this->applyDataAttributeValues();
        $dataImporter = $this->dataImporter;
        $dataImporter->source = new ArraySource([
            'data' => $textData
        ]);
        $executed = $this->executor
            ? $this->executor->execute($dataImporter)
            : $dataImporter->execute();
        if ($dataImporter->getErrors()) {
            $this->addError($this->textAttribute, "Error:" . Json::encode($dataImporter->getErrors()));
        } elseif ($executed === false) {
            $this->addError($this->textAttribute, "Unknown Error");
        } else {
            $this->affectedRowCounts = $dataImporter->getAffectedRowCounts();
        }
        return !$this->hasErrors();
    }

    /**
     * @inheritdoc
     */
    protected function applyDataAttributeValues(): void
    {
        if ($this->dataAttributes) {
            $dataAttributeValues = $this->getAttributes($this->dataAttributes);
            $fillOwnerIdTransformer = new FillDefaultValueTransformer(['defaultValues' => $dataAttributeValues]);
            $transformer = $this->dataImporter->transformer;
            if ($transformer instanceof ChainedTransformer) {
                array_unshift($transformer->transformers, $fillOwnerIdTransformer);
            } else {
                $this->dataImporter->transformer = new ChainedTransformer([
                    'transformers' => array_filter([$fillOwnerIdTransformer, $transformer])
                ]);
            }
        }
    }
}
