<?php

namespace lujie\template\document\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[DocumentFile]].
 *
 * @method DocumentFileQuery id($id)
 * @method DocumentFileQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method DocumentFileQuery documentFileId($documentFileId)
 * @method DocumentFileQuery documentType($documentType)
 * @method DocumentFileQuery referenceId($referenceId)
 * @method DocumentFileQuery referenceNo($referenceNo, bool $like = false)
 * @method DocumentFileQuery documentNo($documentNo, bool $like = false)
 *
 * @method array|DocumentFile[] all($db = null)
 * @method array|DocumentFile|null one($db = null)
 * @method array|DocumentFile[] each($batchSize = 100, $db = null)
 *
 * @see DocumentFile
 */
class DocumentFileQuery extends \yii\db\ActiveQuery
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
                    'documentFileId' => 'document_file_id',
                    'documentType' => 'document_type',
                    'referenceId' => 'reference_id',
                    'referenceNo' => 'reference_no',
                    'documentNo' => 'document_no',
                ]
            ]
        ];
    }

}
