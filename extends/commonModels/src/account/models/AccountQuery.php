<?php

namespace lujie\common\account\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Account]].
 *
 *
 * @method AccountQuery id($id)
 * @method AccountQuery orderById($sort = SORT_ASC)
 * @method AccountQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method AccountQuery accountId($accountId)
 * @method AccountQuery modelType($modelType)
 * @method AccountQuery type($type)
 * @method AccountQuery status($status)
 * @method AccountQuery name($name, bool|string $like = false)
 * @method AccountQuery username($username, bool|string $like = false)
 *
 * @method AccountQuery createdAtBetween($from, $to = null)
 * @method AccountQuery updatedAtBetween($from, $to = null)
 *
 * @method AccountQuery active()
 * @method AccountQuery inActive()
 *
 * @method AccountQuery orderByAccountId($sort = SORT_ASC)
 * @method AccountQuery orderByCreatedAt($sort = SORT_ASC)
 * @method AccountQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method AccountQuery indexByAccountId()
 *
 * @method array getAccountIds()
 *
 * @method array|Account[] all($db = null)
 * @method array|Account|null one($db = null)
 * @method array|Account[] each($batchSize = 100, $db = null)
 *
 * @see Account
 */
class AccountQuery extends \yii\db\ActiveQuery
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
                    'accountId' => 'account_id',
                    'modelType' => 'model_type',
                    'type' => 'type',
                    'status' => 'status',
                    'name' => 'name',
                    'username' => 'username',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [
                    'active' => ['status' => StatusConst::STATUS_ACTIVE],
                    'inActive' => ['status' => StatusConst::STATUS_INACTIVE],
                ],
                'querySorts' => [
                    'orderByAccountId' => 'account_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByAccountId' => 'account_id',
                ],
                'queryReturns' => [
                    'getAccountIds' => ['account_id', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function clearWhereAppendOnCondition(): ActiveQuery
    {
        /** @var Account $modelClass */
        $modelClass = $this->modelClass;
        return $this->where([])->andOnCondition(['model_type' => $modelClass::MODEL_TYPE]);
    }
}
