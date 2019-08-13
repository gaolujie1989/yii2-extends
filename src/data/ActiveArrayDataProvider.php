<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\data;

use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

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
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function prepareModels(): array
    {
        if ($this->isPrepareArray()) {
            $this->query->asArray();
        }
        $models = parent::prepareModels();
        $models = array_map([$this->query->modelClass, $this->prepareArrayMethod], $models);
        return $models;
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function isPrepareArray(): bool
    {
        return $this->query instanceof ActiveQuery && method_exists($this->query->modelClass, $this->prepareArrayMethod);
    }
}
