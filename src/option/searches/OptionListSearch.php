<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\searches;

use lujie\common\option\OptionManager;
use lujie\extend\helpers\ValueHelper;
use yii\base\Model;
use yii\di\Instance;

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

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type'], 'required'],
            [['key'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function validateType(): void
    {
        if (is_string($this->type)) {
            $this->type = ValueHelper::strToArray($this->type);
        }
    }

    /**
     * @return array|bool
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
        foreach ($this->type as $type) {
            $typeOptions[$type] = $this->optionManager->getOptions($type, $this->key ?: '');
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
            'options' => 'options',
        ];
    }
}
