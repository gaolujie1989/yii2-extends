<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\data;

use lujie\extend\helpers\ActiveDataHelper;
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
            $models = array_map([$this->query->modelClass, $this->prepareArrayMethod], $models);
        }
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
