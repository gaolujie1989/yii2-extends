<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\searches;

use lujie\common\option\OptionManager;
use lujie\extend\helpers\ValueHelper;
use yii\base\Model;
use yii\di\Instance;
use yii\helpers\Json;

/**
 * Class OptionSearch
 * @package lujie\common\option\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionListSearch extends Model
{
    /**
     * @var OptionManager
     */
    public $optionManager = 'optionManager';

    /**
     * @var array
     */
    public $options = [];

    public $type;

    public $key;

    public $values;

    public $params;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type'], 'required'],
            [['key'], 'default', 'value' => ''],
            [['key'], 'string'],
            [['values'], 'validateValues'],
            [['params'], 'validateParams'],
            [['values', 'params'], 'each', 'rule' => ['safe']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function validateValues(): void
    {
        if (!is_array($this->values)) {
            $this->values = ValueHelper::strToArray((string)$this->values);
        }
    }

    /**
     * @inheritdoc
     */
    public function validateParams(): void
    {
        if ($this->params && is_string($this->params)) {
            $this->params = Json::decode($this->params);
        }
        if (!is_array($this->params)) {
            $this->params = [];
        }
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function getOptions(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $this->optionManager = Instance::ensure($this->optionManager, OptionManager::class);

        $typeOptions  = [];
        foreach ((array)$this->type as $type) {
            $typeOptions[$type] = $this->optionManager->getOptions($type, $this->key, $this->values, $this->params);
        }
        $this->options = $typeOptions;
        return true;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return [
            'type' => 'type',
            'key' => 'key',
            'values' => 'values',
            'params' => 'params',
            'options' => 'options',
        ];
    }
}
