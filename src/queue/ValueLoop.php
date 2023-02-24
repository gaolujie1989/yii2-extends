<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\queue;

use lujie\data\loader\DataLoaderInterface;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\queue\cli\LoopInterface;

/**
 * Class ValueLoop
 * @package lujie\extend\queue\web
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ValueLoop extends BaseObject implements LoopInterface
{
    /**
     * @var DataLoaderInterface
     */
    public $valueLoader;

    public $exitKey = 'loopExit';

    public $pauseKey = 'loopPause';

    private static $exit = false;

    private static $pause = false;

    public $parseWait = 5;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->valueLoader = Instance::ensure($this->valueLoader, DataLoaderInterface::class);
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function canContinue(): bool
    {
        static::$exit = $this->valueLoader->get($this->exitKey) ?: false;
        static::$pause = $this->valueLoader->get($this->pauseKey) ?: false;
        while (self::$pause && !self::$exit) {
            sleep($this->parseWait);
            static::$exit = $this->valueLoader->get($this->exitKey) ?: false;
            static::$pause = $this->valueLoader->get($this->pauseKey) ?: false;
        }
        return !self::$exit;
    }
}