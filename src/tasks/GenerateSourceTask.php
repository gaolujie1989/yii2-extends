<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tasks;

use lujie\data\loader\DataLoaderInterface;
use lujie\data\recording\forms\GenerateSourceForm;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\VarDumper;

/**
 * Class GenerateSourceTask
 * @package kiwi\data\recording\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class GenerateSourceTask extends CronTask
{
    /**
     * @var DataLoaderInterface
     */
    public $sourceGeneratorLoader = 'dataSourceGeneratorLoader';

    /**
     * @var DataLoaderInterface
     */
    public $dataAccountLoader = 'dataAccountLoader';

    /**
     * @var array
     */
    public $sourceTypes = [];

    /**
     * @var int seconds
     */
    public $timeDurationSeconds = 300;

    /**
     * @var array
     */
    public $formConfig = [];

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public function execute(): bool
    {
        if (empty($this->sourceTypes)) {
            return true;
        }

        $invalidTypeAccounts = [];
        $this->dataAccountLoader = Instance::ensure($this->dataAccountLoader, DataLoaderInterface::class);
        $eachAccounts = $this->dataAccountLoader->each();
        foreach ($eachAccounts as $accountId => $account) {
            if (empty($this->sourceTypes[$account['type']])) {
                continue;
            }
            /** @var GenerateSourceForm $form */
            $form = Instance::ensure($this->formConfig, GenerateSourceForm::class);
            $form->sourceGeneratorLoader = $this->sourceGeneratorLoader;
            $form->sourceTypes = $this->sourceTypes[$account['type']];
            $form->dataAccountId = $accountId;
            $form->endTime = time();
            $form->startTime = $form->endTime - $this->timeDurationSeconds;
            if ($form->generate() === false && $form->hasErrors()) {
                $invalidTypeAccounts[$account['type'] . ':' . $accountId] = $form->getErrors();
            }
        }
        if ($invalidTypeAccounts) {
            $message = 'Invalid account type: ' . VarDumper::dumpAsString($invalidTypeAccounts);
            throw new InvalidConfigException($message);
        }
        return true;
    }
}
