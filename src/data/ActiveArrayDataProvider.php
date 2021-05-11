<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\data;

use lujie\extend\helpers\ActiveDataHelper;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;

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
        if ($this->query->asArray && $this->typecast) {
            $models = ActiveDataHelper::typecast($this->query->modelClass, $models);
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
}
