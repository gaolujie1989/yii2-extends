<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tasks;

use lujie\data\loader\DataLoaderInterface;
use lujie\data\recording\forms\GenerateSourceForm;
use lujie\data\recording\models\DataAccount;
use lujie\scheduling\CronTask;
use Yii;
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
        /** @var DataAccount[] $eachAccount */
        $eachAccount = DataAccount::find()->active()
            ->type(array_keys($this->sourceTypes))
            ->each();
        foreach ($eachAccount as $account) {
            $form = Instance::ensure($this->formConfig, GenerateSourceForm::class);
            $form->sourceGeneratorLoader = $this->sourceGeneratorLoader;
            $form->sourceTypes = $this->sourceTypes[$account->type];
            $form->dataAccountId = $account->data_account_id;
            $form->endTime = time();
            $form->startTime = $form->endTime - $this->timeDurationSeconds;
            if ($form->generate() === false && $form->hasErrors()) {
                $invalidTypeAccounts[$account->data_account_id . $account->name] = $account->type;
            }
        }
        if ($invalidTypeAccounts) {
            $message = 'Invalid account type: ' . VarDumper::dumpAsString($invalidTypeAccounts);
            Yii::error($message, __METHOD__);
            throw new InvalidConfigException($message);
        }
        return true;
    }
}
