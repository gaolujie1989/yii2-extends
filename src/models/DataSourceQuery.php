<?php

namespace lujie\data\recording\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\constants\StatusConst;

/**
 * This is the ActiveQuery class for [[DataRecord]].
 *
 * @method DataSourceQuery id($id)
 * @method DataSourceQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method DataSourceQuery dataSourceId($dataSourceId)
 * @method DataSourceQuery dataAccountId($dataAccountId)
 * @method DataSourceQuery type($type)
 * @method DataSourceQuery status($status)
 * @method DataSourceQuery active()
 * @method DataSourceQuery inactive()
 * @method DataSourceQuery pending()
 * @method DataSourceQuery queued()
 *
 * @method array|DataSource[] all($db = null)
 * @method array|DataSource|null one($db = null)
 * @method array|DataSource[] each($batchSize = 100, $db = null)
 *
 * @see DataSource
 */
class DataSourceQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'dataSourceId' => 'data_source_id',
                    'dataAccountId' => 'data_account_id',
                    'type' => 'type',
                    'status' => 'status',
                ],
                'queryConditions' => [
                    'active' => ['status' => StatusConst::STATUS_ACTIVE],
                    'inactive' => ['status' => StatusConst::STATUS_INACTIVE],
                    'pending' => ['last_exec_status' => ExecStatusConst::EXEC_STATUS_PENDING],
                    'queued' => ['last_exec_status' => ExecStatusConst::EXEC_STATUS_QUEUED],
                ],
            ]
        ];
    }
}
