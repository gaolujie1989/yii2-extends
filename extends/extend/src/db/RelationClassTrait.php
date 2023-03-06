<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use lujie\extend\helpers\ClassHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\helpers\StringHelper;

/**
 * Trait RelationClassTrait
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait RelationClassTrait
{
    /**
     * @param string $class
     * @return string|null
     * @inheritdoc
     */
    protected function getRelationClass(string $class): ?string
    {
        if (StringHelper::endsWith(static::class, 'Search')) {
            $searchClass = ClassHelper::getSearchClass($class);
            if ($searchClass) {
                return $searchClass;
            }
        }
        if (StringHelper::endsWith(static::class, 'Form')) {
            $formClass = ClassHelper::getFormClass($class);
            if ($formClass) {
                return $formClass;
            }
        }
        return null;
    }

    /**
     * @param string $class
     * @param array $link
     * @return ActiveQueryInterface|ActiveQuery
     * @inheritdoc
     */
    public function hasOne($class, $link): ActiveQueryInterface
    {
        $class = $this->getRelationClass($class) ?: $class;
        return parent::hasOne($class, $link);
    }

    /**
     * @param string $class
     * @param array $link
     * @return ActiveQueryInterface|ActiveQuery
     * @inheritdoc
     */
    public function hasMany($class, $link): ActiveQueryInterface
    {
        $class = $this->getRelationClass($class) ?: $class;
        return parent::hasMany($class, $link);
    }
}
