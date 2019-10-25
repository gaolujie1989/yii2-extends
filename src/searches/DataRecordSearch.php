<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\searches;

use lujie\data\recording\models\DataAccount;
use lujie\data\recording\models\DataRecord;
use lujie\extend\compressors\GzCompressor;
use yii\db\ActiveQuery;

/**
 * Class DataRecordSearch
 * @package lujie\data\recording\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataRecordSearch extends DataRecord
{
    public $accountName;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['data_account_id', 'data_source_type', 'data_id', 'data_key', 'data_parent_id'], 'safe'],
            [['accountName'], 'safe'],
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function query(): ActiveQuery
    {
        if ($this->accountName) {
            $this->data_account_id = DataAccount::find()->name($this->accountName)->getAccountId() ?: 0;
        }
        return static::find()->andFilterWhere(
            $this->getAttributes(['data_account_id', 'data_source_type', 'data_id', 'data_key', 'data_parent_id'])
        );
    }
}
