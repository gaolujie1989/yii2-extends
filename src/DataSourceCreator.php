<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging;

use lujie\data\staging\forms\DataAccountForm;
use lujie\data\staging\models\DataAccount;
use lujie\data\staging\models\DataSource;
use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\AfterSaveEvent;
use yii\helpers\ArrayHelper;

/**
 * Class DataSourceCreator
 * @package lujie\data\staging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataSourceCreator extends BaseObject implements BootstrapInterface
{
    /**
     * @var string
     */
    public $accountType;

    /**
     * @var array
     */
    public $sourceTypes = [];

    /**
     * @var array
     */
    public $sourceConfig = [];

    /**
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(DataAccountForm::class, DataAccountForm::EVENT_AFTER_INSERT, [$this, 'afterDataAccountCreated']);
        Event::on(DataAccountForm::class, DataAccountForm::EVENT_AFTER_UPDATE, [$this, 'afterDataAccountCreated']);
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterDataAccountSaved(AfterSaveEvent $event)
    {
        if ($event->sender instanceof DataAccount) {
            $this->createSources($event->sender);
        }
    }

    /**
     * @param DataAccount $account
     * @inheritdoc
     */
    public function createSources(DataAccount $account): void
    {
        if ($account->type !== $this->accountType) {
            return;
        }

        $dataSources = ArrayHelper::index($account->dataSources, 'type');
        foreach ($this->sourceTypes as $sourceType) {
            if (isset($dataSources[$sourceType])) {
                continue;
            }
            $dataSource = new DataSource($this->sourceConfig);
            $dataSource->type = $sourceType;
            $dataSource->data_account_id = $account->data_account_id;
            $dataSource->name = $account->name . '_' . $sourceType;
            $dataSource->save(false);
        }
    }
}
