<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\validators;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\validators\Validator;

/**
 * Class LinkerValidator
 *
 * All config and function like ExistValidator
 * add link extra data from link table
 * config add linkAttributes to defined link attributes
 *
 * ['type_id', 'exist', 'targetClass' => ProductType::class, 'targetAttribute' => ['type_id' => 'id'], linkAttributes = ['type_code' => 'code']],
 * ['type_id', 'exist', 'targetRelation' => 'type', linkAttributes = ['type_code' => 'code']],
 *
 * @package lujie\extend\validators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class LinkerValidator extends Validator
{
    /**
     * @var string|BaseActiveRecord
     */
    public $targetClass;

    /**
     * @var string|array
     */
    public $targetAttribute;

    /**
     * @var string
     */
    public $targetRelation;

    /**
     * ['targetAttribute' => 'modelAttribute']
     * @var array
     */
    public $linkAttributes;

    /**
     * @var string|array|callable
     */
    public $filter;

    /**
     * @var bool
     */
    public $forceMasterDb = true;

    /**
     * @var int
     */
    public $defaultValue = 0;

    /**
     * @var bool
     */
    public $checkExists = true;

    /**
     * @var bool
     */
    public $skipOnEmpty = false;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', '{attribute} is invalid.');
        }
        if (empty($this->linkAttributes) && $this->targetClass) {
            $primaryKey = $this->targetClass::primaryKey();
            $this->linkAttributes = array_combine($primaryKey, $primaryKey);
        }
    }

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @throws \Throwable
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute): void
    {
        $attributeValue = $model->$attribute;
        if (empty($attributeValue)) {
            foreach ($this->linkAttributes as $targetAttr => $modelAttr) {
                $model->{$modelAttr} = $this->defaultValue;
            }
            return;
        }
        if ($this->targetRelation) {
            /** @var ActiveQuery $activeQuery */
            $activeQuery = $model->{'get' . ucfirst($this->targetRelation)}();
        } else {
            $targetAttribute = $this->targetAttribute ?: $attribute;
            if (is_array($targetAttribute)) {
                $condition = [];
                foreach ($targetAttribute as $modelAttr => $targetAttr) {
                    if (is_int($modelAttr)) {
                        $modelAttr = $targetAttr;
                    }
                    $condition[$targetAttr] = $model->$modelAttr;
                }
            } else {
                $condition = [$targetAttribute => $attributeValue];
            }

            /** @var BaseActiveRecord $targetClass */
            $targetClass = $this->targetClass ?: get_class($model);
            $activeQuery = $targetClass::find()->andWhere($condition);
        }

        $this->filterQuery($activeQuery);
        $queryData = $this->queryData($activeQuery);
        if (empty($queryData) && $this->checkExists) {
            $this->addError($model, $attribute, $this->message);
            return;
        }
        foreach ($this->linkAttributes as $targetAttr => $modelAttr) {
            if (is_int($targetAttr)) {
                $targetAttr = $modelAttr;
            }
            $model->{$modelAttr} = $queryData[$targetAttr] ?? $this->defaultValue;
        }
    }

    /**
     * @param ActiveQueryInterface $query
     * @inheritdoc
     */
    protected function filterQuery(ActiveQueryInterface $query): void
    {
        if (empty($this->filter)) {
            return;
        }
        if (is_callable($this->filter)) {
            call_user_func($this->filter, $query);
        } else {
            $query->andWhere($this->filter);
        }
    }

    /**
     * @param ActiveQueryInterface $query
     * @return array|null
     * @throws \Throwable
     * @inheritdoc
     */
    protected function queryData(ActiveQueryInterface $query): ?array
    {
        if (property_exists($query, 'modelClass')) {
            $modelClass = $query->modelClass;
        } else {
            $modelClass = $this->targetClass;
        }
        /** @var BaseActiveRecord $modelClass */
        $db = $modelClass::getDb();
        $query->asArray();
        if ($this->forceMasterDb && $db instanceof Connection) {
            return $db->useMaster(function () use ($query) {
                return $query->one() ?: null;
            });
        }
        return $query->one() ?: null;
    }
}
