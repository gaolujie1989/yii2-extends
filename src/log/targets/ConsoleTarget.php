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
    /**
     * @var bool If true context message will be added to the end of output
     */
    public $enableContextMassage = false;

    /**
     * @var string
     */
    public $dateFormat = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    public $labelTemplate = '[{datetime}][{level}][{memory}][{category}]';

    /**
     * @var int
     */
    public $labelPadSize = 30;

    /**
     * @inheritdoc
     * @return string
     */
    protected function getContextMessage(): string
    {
        return $this->enableContextMassage ? parent::getContextMessage() : '';
    }

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
     * @param $message
     * @return string
     * @inheritdoc
     */
    private function generateLabel($message): string
    {
        $labelData = [
            'datetime' => date($this->dateFormat, $message[3]),
            'category' => $message[2],
            'level' => Logger::getLevelName($message[1]),
            'memory' => number_format(($message[5] ?? 0) / 1024 / 1024, 2) . ' MB',
        ];

        $labelText = TemplateHelper::render($this->labelTemplate, $labelData);
        return str_pad($labelText, $this->labelPadSize, ' ');
    }

    /**
     * @param $message
     * @return string
     * @inheritdoc
     */
    private function generateText($message): string
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
