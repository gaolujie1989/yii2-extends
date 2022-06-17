<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\psr\log;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Yii;
use yii\base\BaseObject;
use yii\log\Logger;

/**
 * Class Yii2Logger
 * @package lujie\as2
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Yii2Logger extends BaseObject implements LoggerInterface
{
    public $category = 'application';

    public function emergency($message, array $context = array()): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array()): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = array()): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = array()): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = array()): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = array()): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = array()): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = array()): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = array()): void
    {
        $yiiLogLevels = [
            LogLevel::EMERGENCY => Logger::LEVEL_ERROR,
            LogLevel::ALERT => Logger::LEVEL_ERROR,
            LogLevel::CRITICAL => Logger::LEVEL_ERROR,
            LogLevel::ERROR => Logger::LEVEL_ERROR,
            LogLevel::WARNING => Logger::LEVEL_WARNING,
            LogLevel::NOTICE => Logger::LEVEL_INFO,
            LogLevel::INFO => Logger::LEVEL_INFO,
            LogLevel::DEBUG => Logger::LEVEL_TRACE,
        ];
        Yii::getLogger()->log($context ? [$message, $context] : $message, $yiiLogLevels[$level], $this->category);
    }
}