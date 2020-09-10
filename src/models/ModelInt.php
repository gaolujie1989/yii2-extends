<?php

namespace lujie\eav\models;

/**
 * Class ModelInt
 * @package lujie\eav\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelInt extends ModelValue
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_int}}';
    }
}
