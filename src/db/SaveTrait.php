<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use Yii;
use yii\db\BaseActiveRecord;
use yii\db\Exception;
use yii\helpers\VarDumper;

/**
 * Trait SaveTrait
 * @package lujie\extend\db
 */
trait SaveTrait
{
    /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        /** @var BaseActiveRecord $this */
        if ($attributeNames) {
            if ($this->hasAttribute('updated_at')) {
                $attributeNames[] = 'updated_at';
            }
            if ($this->hasAttribute('updated_by')) {
                $attributeNames[] = 'updated_by';
            }
        }

        $createOrUpdate = $this->getIsNewRecord() ? 'Create' : 'Update';
        $result = parent::save($runValidation, $attributeNames);
        $parts = explode('\\', static::class);
        $className = end($parts);
        $category = get_called_class() . '::save';

        if ($result) {
            $message = "{$createOrUpdate} {$className} {$this->getPrimaryKey()} Success";
            Yii::debug([$message, $this->getAttributes()], $category);
            Yii::info($message, $category);
        } else {
            if ($this->hasErrors()) {
                $message = "{$createOrUpdate} {$className} Failed.";
                Yii::warning([$message, $this->getAttributes(), $this->getErrors()], $category);
            } else {
                $message = "{$createOrUpdate} {$className} Failed With Unknown Error.";
                Yii::error([$message, $this->getAttributes()], $category);
            }
        }
        return $result;
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws Exception
     * @inheritdoc
     */
    public function saveWithException($runValidation = true, $attributeNames = null)
    {
        $createOrUpdate = $this->getIsNewRecord() ? 'Create' : 'Update';
        $parts = explode('\\', static::class);
        $className = end($parts);

        $result = $this->save($runValidation, $attributeNames);
        if ($result) {
            return $result;
        } else if ($this->hasErrors()) {
            $message = "{$createOrUpdate} {$className} Failed. Errors:" . VarDumper::dumpAsString($this->getErrors());
            throw new Exception($message, $this->getErrors());
        } else {
            $message = "{$createOrUpdate} {$className} Failed With Unknown Error.";
            throw new Exception($message);
        }
    }
}
