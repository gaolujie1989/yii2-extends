<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\base;

use lujie\extend\helpers\ClassHelper;
use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use Yii;
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
    private function searchRules(): array
    {
        /** @var $this BaseActiveRecord */
        return array_merge(ModelHelper::searchRules($this), [
            [['key'], 'string'],
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function searchKeyAttributes(): array
    {
        /** @var $this BaseActiveRecord */
        return ModelHelper::filterAttributes($this, ['no', 'key', 'code', 'name', 'title']);
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
    private function searchQuery(ActiveQueryInterface $query = null, string $alias = ''): ActiveQueryInterface
    {
        /** @var $this BaseActiveRecord */
        $query = ModelHelper::query($this, $query, $alias);
        $keyAttributes = $this->searchKeyAttributes();
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
        return static::prepareSearchArray($row);
    }

    /**
     * @param array $row
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    private static function prepareSearchArray(array $row): array
    {
        [$aliasProperties, $relations] = static::getSearchAliasRelations();
        return ModelHelper::prepareArray($row, static::class, $aliasProperties, $relations);
    }

    /**
     * @return mixed
     * @inheritdoc
     */
    private static function getSearchAliasRelations(): array
    {
        if (empty(Yii::$app->params['prepareArray'][static::class])) {
            $modelClass = ClassHelper::getBaseRecordClass(static::class);
            $formClass = ClassHelper::getFormClass($modelClass);
            if ($formClass) {
                /** @var BaseActiveRecord $form */
                $form = new $formClass();
                $aliasProperties = ModelHelper::aliasProperties($form);
                $relations = $form->extraFields();
                Yii::$app->params['prepareArray'][static::class] = [$aliasProperties, $relations];
            } else {
                Yii::$app->params['prepareArray'][static::class] = [[], []];
            }
        }
        return Yii::$app->params['prepareArray'][static::class];
    }
}
