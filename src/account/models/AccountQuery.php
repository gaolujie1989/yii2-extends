<?php

namespace lujie\common\account\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Account]].
 *
 * @method static id($id)
 * @method static orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method static accountId($accountId)
 * @method static modelType($modelType)
 * @method static type($type)
 * @method static status($status)
 * @method static name($name)
 *
 * @method static active()
 * @method static inActive()
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
                ],
                'queryConditions' => [
                    'active' => ['status' => StatusConst::STATUS_ACTIVE],
                    'inActive' => ['status' => StatusConst::STATUS_INACTIVE],
                ],
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
