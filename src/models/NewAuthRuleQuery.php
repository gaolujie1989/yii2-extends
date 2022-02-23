<?php

namespace lujie\auth\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[NewAuthRule]].
 *
 * @method NewAuthRuleQuery id($id)
 * @method NewAuthRuleQuery orderById($sort = SORT_ASC)
 * @method NewAuthRuleQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method NewAuthRuleQuery ruleId($ruleId)
 *
 * @method NewAuthRuleQuery createdAtBetween($from, $to = null)
 * @method NewAuthRuleQuery updatedAtBetween($from, $to = null)
 *
 * @method NewAuthRuleQuery orderByRuleId($sort = SORT_ASC)
 * @method NewAuthRuleQuery orderByCreatedAt($sort = SORT_ASC)
 * @method NewAuthRuleQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method NewAuthRuleQuery indexByRuleId()
 *
 * @method NewAuthRuleQuery getRuleIds()
 *
 * @method array|NewAuthRule[] all($db = null)
 * @method array|NewAuthRule|null one($db = null)
 * @method array|NewAuthRule[] each($batchSize = 100, $db = null)
 *
 * @see NewAuthRule
 */
class NewAuthRuleQuery extends \yii\db\ActiveQuery
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
                    'ruleId' => 'rule_id',
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByRuleId' => 'rule_id',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByRuleId' => 'rule_id',
                ],
                'queryReturns' => [
                    'getRuleIds' => 'rule_id',
                ]
            ]
        ];
    }

}
