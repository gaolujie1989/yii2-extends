<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording;

use lujie\data\recording\forms\DataAccountForm;
use lujie\data\recording\models\DataAccount;
use lujie\data\recording\models\DataSource;
use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\AfterSaveEvent;
use yii\helpers\ArrayHelper;

/**
 * Class DataSourceCreator
 * @package lujie\data\recording
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
    public $sourceTypeConfigs = [];

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->accountType)) {
            throw new InvalidConfigException('The property `accountType` must be set');
        }
        if (empty($this->sourceTypes)) {
            throw new InvalidConfigException('The property `sourceTypes` must be set');
        }
    }

    /**
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(DataAccountForm::class, DataAccountForm::EVENT_AFTER_INSERT, [$this, 'afterDataAccountSaved']);
        Event::on(DataAccountForm::class, DataAccountForm::EVENT_AFTER_UPDATE, [$this, 'afterDataAccountSaved']);
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
            $sourceConfig = $this->sourceTypeConfigs[$sourceType] ?? [];
            $dataSource = new DataSource($sourceConfig);
            $dataSource->type = $sourceType;
            $dataSource->data_account_id = $account->data_account_id;
            $dataSource->name = $account->name . '_' . $sourceType;
            $dataSource->save(false);
        }
    }
}
