<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\forms;

use lujie\data\loader\DataLoaderInterface;
use lujie\data\recording\BaseDataSourceGenerator;
use yii\base\Model;
use yii\di\Instance;

/**
 * Class GenerateSourceForm
 * @package kiwi\data\recording\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class GenerateSourceForm extends Model
{
    /**
     * @var DataLoaderInterface
     */
    public $sourceGeneratorLoader = 'dataSourceGeneratorLoader';

    /**
     * @var int
     */
    public $dataAccountId;

    /**
     * @var array
     */
    public $sourceTypes;

    /**
     * @var int
     */
    public $startTime;

    /**
     * @var int
     */
    public $endTime;

    /**
     * @var int
     */
    public $timePeriod;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['dataAccountId', 'sourceTypes', 'startTime', 'endTime'], 'required'],
            [['startTime', 'endTime'], 'date'],
            [['sourceTypes'], 'each', 'rule' => ['string']],
            [['dataAccountId', 'timePeriod'], 'integer'],
        ];
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function generate(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $this->sourceGeneratorLoader = Instance::ensure($this->sourceGeneratorLoader, DataLoaderInterface::class);
        $sourceGenerator = $this->sourceGeneratorLoader->get($this->dataAccountId);
        if ($sourceGenerator === null) {
            $this->addError('dataAccountId', 'Invalid dataAccountId, Null DataSourceGenerator');
            return false;
        }

        /** @var BaseDataSourceGenerator $sourceGenerator */
        $sourceGenerator = Instance::ensure($sourceGenerator, BaseDataSourceGenerator::class);
        $sourceGenerator->generateSources($this->dataAccountId, $this->sourceTypes, $this->startTime, $this->endTime, $this->timePeriod);
        return true;
    }
}
