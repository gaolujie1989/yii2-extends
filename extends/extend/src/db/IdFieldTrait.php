<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use lujie\extend\helpers\ModelHelper;
use yii\db\BaseActiveRecord;

/**
 * Trait IdFieldTrait
 *
 * @property int|string $id
 *
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @deprecated copy from AliasFieldTrait, use AliasFieldTrait instead
 */
trait IdFieldTrait
{
    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return $this->aliasFields();
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function aliasFields(): array
    {
        /** @var BaseActiveRecord $this */
        return array_merge(parent::fields(), ModelHelper::aliasFields($this), ['id' => 'id']);
    }

    #region ID get/set

    /**
     * @return int|string
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
     * @param int|string|array $id
     * @inheritdoc
     */
    public function setId($id): void
    {
        /** @var BaseActiveRecord $model */
        $model = $this;
        if ($model->hasAttribute('id')) {
            $model->setAttribute('id', $id);
            return;
        }
        $keys = $model::primaryKey();
        if (count($keys) === 1) {
            $model->setAttribute($keys[0], $id);
        } else {
            foreach ($keys as $index => $name) {
                $value = $id[$name] ?? $id[$index] ?? null;
                $model->setAttribute($name, $value);
            }
        }
    }

    #endregion
}
