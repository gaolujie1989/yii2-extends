<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

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
    protected function formRules(): array
    {
        /** @var $this BaseActiveRecord */
        return ModelHelper::formRules($this, parent::rules());
    }
}
