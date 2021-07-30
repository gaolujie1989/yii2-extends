<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;

/**
 * Trait SearchTrait
 * @package lujie\extend\base
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait SearchTrait
{
    /**
     * @var string
     */
    public $key;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return $this->searchRules();
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function searchRules(): array
    {
        /** @var BaseActiveRecord $this */
        return array_merge(ModelHelper::searchRules($this), [
            [['id', 'key'], 'string'],
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function searchKeyAttributes(): array
    {
        /** @var BaseActiveRecord $this */
        return ModelHelper::filterAttributes($this->attributes(), ['no', 'key', 'code', 'name', 'title']);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return $this->searchBehaviors();
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function searchBehaviors(): array
    {
        $behaviors = parent::behaviors();
        if (method_exists($this, 'aliasBehaviors')) {
            $behaviors = array_merge($behaviors, $this->aliasBehaviors());
        }
        return $behaviors;
    }

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        return $this->searchQuery();
    }

    /**
     * @return ActiveQueryInterface|ActiveQuery
     * @inheritdoc
     */
    protected function searchQuery(ActiveQueryInterface $query = null, string $alias = ''): ActiveQueryInterface
    {
        $keyAttributes = $this->searchKeyAttributes();
        /** @var BaseActiveRecord $this */
        $query = ModelHelper::query($this, $query, $alias);
        if ($this->key && $keyAttributes) {
            QueryHelper::filterKey($query, $keyAttributes, $this->key, true);
        }
        return $query;
    }

    /**
     * @param array $row
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        return self::prepareSearchArray($row);
    }

    /**
     * @param array $row
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    public static function prepareRows(array $rows): array
    {
        return array_map([static::class, 'prepareArray'], $rows);
    }

    /**
     * @param $row
     * @param array $aliasProperties
     * @param array $relations
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    protected static function prepareSearchArray($row, array $aliasProperties = [], array $relations = []): array
    {
        return ModelHelper::prepareArray($row, static::class, $aliasProperties, $relations);
    }
}
