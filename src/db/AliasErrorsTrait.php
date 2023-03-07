<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use lujie\extend\helpers\ClassHelper;
use yii\db\BaseActiveRecord;
use yii\db\Exception;

/**
 * Trait AliasErrorsTrait
 *
 * @property array $aliasErrorAttributes = [];
 *
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait AliasErrorsTrait
{
    /**
     * @param $attribute
     * @param string $error
     * @inheritdoc
     */
    public function addError($attribute, $error = ''): void
    {
        $aliasErrorAttribute = $this->aliasErrorAttributes[$attribute] ?? null;
        if (isset($aliasErrorAttribute)) {
            $error = strtr($error, [
                $this->{$attribute} => $this->{$aliasErrorAttribute},
                $this->getAttributeLabel($attribute) => $this->getAttributeLabel($aliasErrorAttribute),
            ]);
        }
        parent::addError($attribute, $error);
    }
}
