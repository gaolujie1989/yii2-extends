<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging;

use lujie\data\staging\models\DataAccount;
use lujie\data\staging\models\DataSource;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * Class DataSourceCreator
 * @package lujie\data\staging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataSourceCreator extends BaseObject
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
     * @param DataAccount $account
     * @inheritdoc
     */
    public function createSourceS(DataAccount $account): void
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
            $dataSource->name = $sourceType . '_' . $account->name;
            $dataSource->save(false);
        }
    }
}
