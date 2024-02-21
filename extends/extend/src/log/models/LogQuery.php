<?php

namespace lujie\extend\log\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[Log]].
 *
 * @method LogQuery id($id)
 * @method LogQuery orderById($sort = SORT_ASC)
 * @method LogQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method LogQuery logAtBetween($from, $to = null)
 * @method LogQuery orderByLogAt($sort = SORT_ASC)
 *
 * @method array|Log[] all($db = null)
 * @method array|Log|null one($db = null)
 * @method array|Log[] each($batchSize = 100, $db = null)
 *
 * @see Log
 */
class LogQuery extends \yii\db\ActiveQuery
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
                    'logAtBetween' => ['log_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByLogAt' => 'log_at',
                ],
                'queryIndexes' => [
                ],
                'queryReturns' => [
                ]
            ]
        ];
    }

}
