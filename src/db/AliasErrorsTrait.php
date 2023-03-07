<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use lujie\extend\helpers\ClassHelper;
use yii\db\BaseActiveRecord;
use yii\db\Exception;

/**
 * Trait SaveTrait
 * @package lujie\extend\db
 */
trait AliasErrorsTrait
{
    public $aliasErrorAttributes = [];

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
                $this->getAttribute($attribute) => $this->getAttribute($aliasErrorAttribute),
                $this->getAttributeLabel($attribute) => $this->getAttributeLabel($aliasErrorAttribute),
            ]);
        }
        parent::addError($attribute, $error);
    }
}
