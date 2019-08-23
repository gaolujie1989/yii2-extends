<?php

namespace lujie\template\document\forms;

use lujie\extend\helpers\ModelRuleHelper;
use lujie\template\document\models\DocumentTemplate;
use yii2tech\ar\position\PositionBehavior;

/**
 * Class DocumentTemplateForm
 * @package lujie\template\document\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DocumentTemplateForm extends DocumentTemplate
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules = ModelRuleHelper::removeAttributesRules($rules, ['position']);
        return $rules;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'position' => [
                'class' => PositionBehavior::class,
                'groupAttributes' => ['type', 'document_reference_id'],
            ]
        ]);
    }
}
