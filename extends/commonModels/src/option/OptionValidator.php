<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option;

use Yii;
use yii\di\Instance;
use yii\validators\Validator;
use yii\web\NotFoundHttpException;

/**
 * Class OptionManager
 * @package lujie\common\option
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionValidator extends Validator
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var OptionManager
     */
    public $optionManager;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', 'Invalid option.');
        }
        $this->optionManager = Instance::ensure($this->optionManager, OptionManager::class);
    }

    /**
     * @param mixed $value
     * @return array|null
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function validateValue($value): ?array
    {
        $options = $this->optionManager->getOptions($this->type, $value, false);
        if (empty($options)) {
            return [$this->message, []];
        }
        return null;
    }
}