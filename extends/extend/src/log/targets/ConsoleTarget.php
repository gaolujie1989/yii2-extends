<?php
/**
 * @link http://www.yiiframework.com/
 * @license http://www.yiiframework.com/license/
 */

namespace lujie\extend\log\targets;

use lujie\extend\helpers\TemplateHelper;
use yii\helpers\Console;
use yii\helpers\VarDumper;
use yii\log\Logger;
use yii\log\Target;

/**
 * ConsoleTarget writes log to console (useful for debugging console applications)
 *
 * @author pahanini <pahanini@gmail.com>
 */
class ConsoleTarget extends Target
{
    use LogContextMassageTrait;

    /**
     * @var string
     */
    public $labelTemplate = '[{datetime}][{level}][{category}][{memory}]';

    /**
     * @var int
     */
    public $labelPadSize = 30;

    /**
     * need set Logger->$flushInterval = 1
     * @var int
     */
    public $exportInterval = 1;

    /**
     * @inheritdoc
     */
    public function export(): void
    {
        foreach ($this->messages as $message) {
            if ($message[1] === Logger::LEVEL_ERROR) {
                Console::error($this->formatMessage($message));
            } else {
                Console::output($this->formatMessage($message));
            }
        }
    }

    /**
     * @param array $message
     * 0 - massage
     * 1 - level
     * 2 - category
     * 3 - timestamp
     * 4 - ???
     *
     * @return string
     */
    public function formatMessage($message): string
    {
        $label = $this->generateLabel($message);
        $text = $this->generateText($message);

        return $label . ' ' . $text;
    }

    /**
     * @param array $message
     * @return string
     * @inheritdoc
     */
    private function generateLabel(array $message): string
    {
        [$text, $level, $category, $timestamp, $traces, $memoryUsage] = $message;
        $labelData = [
            'datetime' => $this->getTime($timestamp),
            'level' => Logger::getLevelName($level),
            'category' => $category,
            'memory' => number_format($memoryUsage / 1024 / 1024, 2) . ' MB',
        ];

        $labelText = TemplateHelper::render($this->labelTemplate, $labelData);
        return str_pad($labelText, $this->labelPadSize, ' ');
    }

    /**
     * @param array $message
     * @return string
     * @inheritdoc
     */
    private function generateText(array $message): string
    {
        $text = $message[0];
        if (is_array($text) || is_object($text)) {
            $text = "Array content is \n\r" . VarDumper::dumpAsString($text);
        } elseif (!is_string($text)) {
            $text = 'Message is ' . gettype($text);
        }
        return $text;
    }
}
