<?php

namespace lujie\template\document\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;

/**
 * This is the ActiveQuery class for [[DocumentTemplate]].
 *
 * @method DocumentTemplateQuery id($id)
 * @method DocumentTemplateQuery type($type);
 * @method DocumentTemplateQuery referenceId($referenceId);
 *
 * @method DocumentTemplateQuery active();
 * @method DocumentTemplateQuery orderByPosition($sort = SORT_ASC);
 *
 * @method DocumentTemplate[]|array all($db = null)
 * @method DocumentTemplate|array|null one($db = null)
 *
 * @see DocumentTemplate
 */
class DocumentTemplateQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'type' => 'document_type',
                    'referenceId' => 'document_reference_id',
                ],
                'queryConditions' => [
                    'active' => ['status' => StatusConst::STATUS_ACTIVE],
                ],
                'querySorts' => [
                    'orderByPosition' => 'position',
                ]
            ]
        ]);
    }
}
