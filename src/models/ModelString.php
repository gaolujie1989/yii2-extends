<?php

namespace lujie\eav\models;

/**
 * Class ModelString
 * @package lujie\eav\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelString extends ModelValue
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_string}}';
    }
}
