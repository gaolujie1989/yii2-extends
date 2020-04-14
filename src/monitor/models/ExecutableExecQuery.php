<?php

namespace lujie\executing\monitor\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use yii\db\ActiveQuery;

/**
 * Class ExecutableExecQuery
 *
 *
 * @method ExecutableExecQuery id($id)
 * @method ExecutableExecQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method ExecutableExecQuery executableExecId($executableExecId)
 * @method ExecutableExecQuery executableId($executableId)
 * @method ExecutableExecQuery execUid($execUid)
 * @method ExecutableExecQuery executor($executor)
 * @method ExecutableExecQuery status($status)
 *
 * @method array|ExecutableExec[] all($db = null)
 * @method array|ExecutableExec|null one($db = null)
 * @method array|ExecutableExec[] each($batchSize = 100, $db = null)
 *
 * @see ExecutableExec
 * @package lujie\executing\monitor\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecutableExecQuery extends ActiveQuery
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
                    'executableExecId' => 'executable_exec_id',
                    'executableId' => 'executable_id',
                    'execUid' => 'executable_exec_uid',
                    'executor' => 'executor',
                    'status' => 'status',
                ]
            ]
        ];
    }
}
