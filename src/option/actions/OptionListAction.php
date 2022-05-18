<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\actions;

use lujie\common\option\models\Option;
use lujie\common\option\OptionManager;
use lujie\extend\helpers\ValueHelper;
use yii\di\Instance;
use yii\rest\Action;

/**
 * Class OptionAction
 * @package lujie\common\option\actions
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionListAction extends Action
{
    /**
     * @var string
     */
    public $modelClass = Option::class;

    /**
     * @var OptionManager
     */
    public $optionManager = 'optionManager';

    /**
     * @param array|string $types
     * @param string $key
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function run($types, string $key = ''): array
    {
        $this->optionManager = Instance::ensure($this->optionManager, OptionManager::class);
        if (is_string($types)) {
            $types = ValueHelper::strToArray($types);
        }
        $typeOptions  = [];
        foreach ($types as $type) {
            $typeOptions[$type] = $this->optionManager->getOptions($type, $key);
        }
        return $typeOptions;
    }
}