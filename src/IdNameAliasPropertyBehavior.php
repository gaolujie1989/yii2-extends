<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\alias\behaviors;

use lujie\alias\db\ReturnFieldQuery;
use yii\db\ActiveRecord;

/**
 * Class IdNameAliasPropertyBehavior
 * @package lujie\alias\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class IdNameAliasPropertyBehavior extends AliasPropertyBehavior
{
    /**
     * [
     *      'aliasXXX' => [
     *          'class' => 'xxx',
     *          'idField' => 'xxx_id',
     *          'nameField' => 'xxx_name',
     *      ]
     * ]
     * @var array
     */
    public $aliasProperties = [];

    /**
     * @param string $name
     * @return mixed
     * @inheritdoc
     */
    public function getAliasProperty(string $name)
    {
        //because it need return query, comment bellow
//        $idValue = parent::getAliasProperty($name);
//        if (empty($idValue)) {
//            return '';
//        }
        /** @var ActiveRecord $modelClass */
        ['class' => $modelClass, 'idField' => $idField, 'nameField' => $nameField] = $this->aliasProperties[$name];
        $query = new ReturnFieldQuery($modelClass, ['returnField' => $nameField]);
        $query->primaryModel = $this->owner;
        $query->link = [$idField => $idField];
        $query->multiple = false;
        return $query;
    }

    /**
     * @param string $name
     * @param $value
     * @inheritdoc
     */
    public function setAliasProperty(string $name, $value): void
    {
        if (empty($value)) {
            parent::setAliasProperty($name, 0);
            return;
        }
        /** @var ActiveRecord $modelClass */
        ['class' => $modelClass, 'idField' => $idField, 'nameField' => $nameField] = $this->aliasProperties[$name];
        $idValue = $modelClass::find()->andWhere([$nameField => $value])->select([$idField])->scalar();
        if ($idValue === null) {
            $this->owner->addError($name, 'Invalid value');
            return;
        }
        parent::setAliasProperty($name, $idValue);
    }
}
