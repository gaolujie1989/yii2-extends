<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\base;

use lujie\extend\helpers\ModelHelper;
use yii\db\BaseActiveRecord;

/**
 * Trait FormTrait
 * @package lujie\extend\base
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait FormTrait
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return $this->formRules();
    }

    /**
     * @return array
     * @inheritdoc
     */
    private function formRules(): array
    {
        /** @var $this BaseActiveRecord */
        return ModelHelper::formRules($this, parent::rules());
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return $this->formFields();
    }

    /**
     * @return array
     * @inheritdoc
     */
    private function formFields(): array
    {
        /** @var $this BaseActiveRecord */
        return array_merge(parent::fields(), ModelHelper::aliasFields($this));
    }
}
