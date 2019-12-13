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
     * @return array
     * @inheritdoc
     */
    public function getTraceableAttributes(): array
    {
        return ['updated_at', 'updated_by'];
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null): bool
    {
        if ($attributeNames) {
            $traceableAttributes = $this->getTraceableAttributes();
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
     * @param array|null $attributeNames
     * @return bool
     * @throws Exception
     * @inheritdoc
     */
    public function mustSave(bool $runValidation = true, ?array $attributeNames = null): bool
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
