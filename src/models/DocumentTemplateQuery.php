<?php

namespace lujie\template\document\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;

/**
 * This is the ActiveQuery class for [[DocumentTemplate]].
 *
 * @method DocumentTemplateQuery id($id)
 * @method DocumentTemplateQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method DocumentTemplateQuery documentTemplateId($documentTemplateId)
 * @method DocumentTemplateQuery documentType($documentType)
 * @method DocumentTemplateQuery referenceId($referenceId)
 * @method DocumentTemplateQuery status($status)
 *
 * @method DocumentTemplateQuery active();
 * @method DocumentTemplateQuery orderByPosition($sort = SORT_ASC);
 *
 * @method array|DocumentTemplate[] all($db = null)
 * @method array|DocumentTemplate|null one($db = null)
 * @method array|DocumentTemplate[] each($batchSize = 100, $db = null)
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
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'documentTemplateId' => 'document_template_id',
                    'documentType' => 'document_type',
                    'referenceId' => 'reference_id',
                    'status' => 'status',
                ],
                'queryConditions' => [
                    'active' => ['status' => StatusConst::STATUS_ACTIVE],
                ],
                'querySorts' => [
                    'orderByPosition' => 'position',
                ]
            ]
        ];
    }
}
