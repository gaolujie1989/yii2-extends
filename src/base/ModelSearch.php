<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\base;

use lujie\extend\helpers\ClassHelper;
use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\QueryHelper;
use Yii;
use yii\base\Model;
use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;

/**
 * Class ModelSearch  // Search can be like BatchForm
 * @package lujie\extend\base
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelSearch extends Model
{
    use ModelAttributeTrait;

    /**
     * @var string
     */
    public static $modelClass;

    /**
     * @var BaseActiveRecord
     */
    protected $model;

    /**
     * @var string
     */
    public $key;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->model = new static::$modelClass();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(ModelHelper::searchRules($this->model), [
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
        return ModelHelper::filterAttributes($this->attributes(), ['no', 'key', 'code', 'name', 'title']);
    }

    /**
     * @param ActiveQueryInterface|null $query
     * @param string $alias
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(ActiveQueryInterface $query = null, string $alias = ''): ActiveQueryInterface
    {
        $query = ModelHelper::query($this->model, $query, $alias);
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
        [$aliasProperties, $relations] = static::getSearchAliasRelations();
        return ModelHelper::prepareArray($row, static::$modelClass, $aliasProperties, $relations);
    }

    /**
     * @return mixed
     * @inheritdoc
     */
    protected static function getSearchAliasRelations(): array
    {
        if (empty(Yii::$app->params['prepareArray'][static::$modelClass])) {
            $modelClass = ClassHelper::getBaseRecordClass(static::$modelClass);
            $formClass = ClassHelper::getFormClass($modelClass);
            if ($formClass) {
                /** @var BaseActiveRecord $form */
                $form = new $formClass();
                $aliasProperties = ModelHelper::aliasProperties($form);
                $relations = $form->extraFields();
                Yii::$app->params['prepareArray'][static::$modelClass] = [$aliasProperties, $relations];
            } else {
                Yii::$app->params['prepareArray'][static::$modelClass] = [[], []];
            }
        }
        return Yii::$app->params['prepareArray'][static::$modelClass];
    }
}
