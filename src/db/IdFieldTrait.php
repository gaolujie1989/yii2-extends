<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use yii\db\BaseActiveRecord;

/**
 * Trait IdFieldsTrait
 * @package lujie\extend\db
 */
trait IdFieldTrait
{
    /**
     * @return mixed
     * @inheritdoc
     */
    public function getId()
    {
        /** @var BaseActiveRecord $model */
        $model = $this;
        if ($model->hasAttribute('id')) {
            return $model->getAttribute('id');
        }
        return $model->getPrimaryKey();
    }

    /**
     * @param $id
     * @inheritdoc
     */
    public function setId($id): void
    {
        /** @var BaseActiveRecord $model */
        $model = $this;
        if ($model->hasAttribute('id')) {
            return $model->setAttribute('id', $id);
        }
        $keys = $model::primaryKey();
        if (count($keys) === 1) {
            $model->setAttribute($keys[0], $id);
        } else {
            foreach ($keys as $index => $name) {
                $value =  $id[$name] ?? $id[$index] ?? null;
                $model->setAttribute($name, $value);
            }
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        $fields = parent::fields();
        $fields['id'] = 'id';
        return $fields;
    }
}
