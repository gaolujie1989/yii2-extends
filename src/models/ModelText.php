<?php

namespace lujie\eav\models;

/**
 * Class ModelText
 * @package lujie\eav\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelText extends ModelValue
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_text}}';
    }
}
