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
trait SaveTrait
{
    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return mixed
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {

        if ($attributeNames) {
            $traceableAttributes = ['updated_at', 'updated_by'];
            foreach ($traceableAttributes as $attribute) {
                /** @var BaseActiveRecord $this */
                if ($this->hasAttribute($attribute)) {
                    $attributeNames[] = $attribute;
                }
            }
        }
        return parent::save($runValidation, $attributeNames);
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return mixed
     * @throws Exception
     * @inheritdoc
     */
    public function mustSave($runValidation = true, $attributeNames = null)
    {
        if ($result = $this->save($runValidation, $attributeNames)) {
            return $result;
        }
        /** @var BaseActiveRecord $this */
        $createOrUpdate = $this->getIsNewRecord() ? 'Create' : 'Update';
        $className = ClassHelper::getClassShortName(static::class);
        $message = "{$createOrUpdate} {$className} Failed.";
        /** @var BaseActiveRecord $this */
        throw new Exception($message, $this->getErrors());
    }
}
