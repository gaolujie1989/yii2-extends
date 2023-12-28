<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\data;

use lujie\extend\helpers\ActiveDataHelper;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;

/**
 * Class ActiveArrayDataProvider
 *
 * @property ActiveQuery $query
 *
 * @package lujie\extend\data
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveArrayDataProvider extends ActiveDataProvider
{
    /**
     * @var string
     */
    public $prepareArrayMethod = 'prepareArray';

    /**
     * @var string
     */
    public $prepareRowsMethod = 'prepareRows';

    /**
     * @var bool
     */
    public $typecast = false;

    /**
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function prepareModels(): array
    {
        $isPrepareArray = $this->isPrepareArray();
        if ($isPrepareArray) {
            $this->query->asArray();
        }
        $models = parent::prepareModels();
        if (empty($models)) {
            return [];
        }
        if ($this->query->asArray && $this->typecast) {
            $models = ActiveDataHelper::typecast($models, $this->query->modelClass);
        }
        if ($isPrepareArray) {
            if (method_exists($this->query->modelClass, $this->prepareRowsMethod)) {
                $models = call_user_func([$this->query->modelClass, $this->prepareRowsMethod], $models);
            } else if (method_exists($this->query->modelClass, $this->prepareArrayMethod)) {
                $models = array_map([$this->query->modelClass, $this->prepareArrayMethod], $models);
            }
        }
        return $models;
    }

    /**
     * @param array $models
     * @return array
     * @inheritdoc
     */
    protected function prepareKeys($models): array
    {
        if (($this->query instanceof ActiveQueryInterface && $this->query->asArray) || $this->isPrepareArray()) {
            return array_keys($models);
        }
        return parent::prepareKeys($models);
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function isPrepareArray(): bool
    {
        return $this->query instanceof ActiveQueryInterface
            && (method_exists($this->query->modelClass, $this->prepareArrayMethod) || method_exists($this->query->modelClass, $this->prepareRowsMethod));
    }

    /**
     * @param array|bool|\yii\data\Sort $value
     * @inheritdoc
     */
    public function setSort($value): void
    {
        parent::setSort($value);
        if ($this->query instanceof ActiveQueryInterface && ($sort = $this->getSort()) !== false) {
            /* @var $modelClass BaseActiveRecord */
            $modelClass = $this->query->modelClass;
            $primaryKey = $modelClass::primaryKey();
            $model = $modelClass::instance();
            if (count($primaryKey) === 1) {
                $pk = reset($primaryKey);
                $sort->attributes['id'] = [
                    'asc' => [$pk => SORT_ASC],
                    'desc' => [$pk => SORT_DESC],
                    'label' => $model->getAttributeLabel($pk),
                ];
            }
            if (method_exists($modelClass, 'sorts')) {
                $sort->attributes = array_merge($sort->attributes, $modelClass::sorts());
                $sort->init();
            }
//            if (method_exists($model, 'sorts')) {
//                $sort->attributes = array_merge($sort->attributes, $model->sorts());
//            }
        }
    }
}
