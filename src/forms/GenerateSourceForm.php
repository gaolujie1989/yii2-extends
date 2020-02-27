<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\forms;

use lujie\data\loader\DataLoaderInterface;
use lujie\data\recording\BaseDataSourceGenerator;
use lujie\data\recording\models\DataAccount;
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
     * @var DataLoaderInterface|mixed
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
     * @var DataAccount
     */
    private $_dataAccount;

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
            ['dataAccountId', 'validateAccountId'],
        ];
    }

    /**
     * @return DataAccount|null
     * @inheritdoc
     */
    protected function getDataAccount(): ?DataAccount
    {
        if ($this->_dataAccount === null) {
            $this->_dataAccount = DataAccount::findOne($this->dataAccountId);
        }
        return $this->_dataAccount;
    }

    /**
     * @inheritdoc
     */
    public function validateAccountId(): void
    {
        if ($this->getDataAccount() === null) {
            $this->addError('dataAccountId', 'Invalid data account id, DataAccount not found');
            return;
        }
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
        $sourceGenerator = $this->sourceGeneratorLoader->get($this->getDataAccount()->type);
        if ($sourceGenerator === null) {
            $this->addError('Invalid data account type, Null DataSourceGenerator');
            return false;
        }

        /** @var BaseDataSourceGenerator $sourceGenerator */
        $sourceGenerator = Instance::ensure($sourceGenerator, BaseDataSourceGenerator::class);
        $sourceGenerator->generateSources($this->getDataAccount(), $this->sourceTypes, $this->startTime, $this->endTime, $this->timePeriod);
        return true;
    }
}
