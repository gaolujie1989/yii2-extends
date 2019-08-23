<?php

namespace lujie\template\document\searches;

use lujie\template\document\models\DocumentTemplate;
use lujie\template\document\models\DocumentTemplateQuery;
use Yii;

/**
 * Class DocumentTemplateSearch
 * @package lujie\template\document\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DocumentTemplateSearch extends DocumentTemplate
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['document_reference_id', 'document_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function query(): DocumentTemplateQuery
    {
        return static::find()->andFilterWhere([
            $this->getAttributes(['document_reference_id', 'document_type'])
        ]);
    }
}
