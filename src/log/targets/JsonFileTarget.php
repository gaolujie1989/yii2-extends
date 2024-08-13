<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\log\targets;

use yii\base\Arrayable;
use yii\helpers\VarDumper;
use yii\log\FileTarget;
use yii\log\Logger;

/**
 * Class JsonFileTarget
 * @package lujie\extend\log\targets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class JsonFileTarget extends FileTarget
{
    use LogContextMassageTrait, LogProfilingTrait;

    /**
     * @var array the messages that need to be profiled on duration bigger.
     */
    public $profilingOn = [
        'yii\db\Command::query' => 0.5,
        'yii\db\Command::execute' => 0.05,
        'yii\httpclient\CurlTransport::send' => 5,
        '*' => 1,
    ];

    /**
     * @param $message
     * @return string
     * @inheritdoc
     */
    public function formatMessage($message): string
    {
        list($text, $level, $category, $timestamp) = $message;
        $level = Logger::getLevelName($level);

        $meta = [];
        $exceptionName = '';
        if (!is_string($text)) {
            // exceptions may not be serializable if in the call stack somewhere is a Closure
            if ($text instanceof \Exception || $text instanceof \Throwable) {
                $exceptionName = $text::class;
                $text = $text->getMessage();
            } else if (is_array($text) || $text instanceof Arrayable) {
                if ($text instanceof Arrayable) {
                    $text = $text->toArray();
                }
                $t = $text['message'] ?? $text['msg'] ?? $text[0] ?? null;
                if ($t) {
                    unset($text['message'], $text['msg'], $text[0]);
                    $meta = $text;
                    $text = $t;
                } else {
                    $text = VarDumper::export($text);
                }
            } else {
                $text = VarDumper::export($text);
            }
        }

        $traces = [];
        if (isset($message[4])) {
            foreach ($message[4] as $trace) {
                $traces[] = "in {$trace['file']}:{$trace['line']}";
            }
        }

        $prefix = $this->getMessagePrefix($message);
        $classParts = explode('\\', $category);
        $module = ucfirst($classParts[0]) . ucfirst($classParts[1] ?? '');
        $msg = array_filter([
            'time' => $this->getTime($timestamp),
            'level' => $level,
            'module' => $module,
            'category' => $category,
            'exception' => $exceptionName,
            'prefix' => $prefix,
            'message' => $text,
            'memory_usage' => $message[5] ?? 0,
            'memory_diff' => $message[6] ?? 0,
            'duration' => $message[7] ?? 0,
            'trace' => $traces,
            'meta' => $meta,
        ]);
        return json_encode($msg);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\log\LogRuntimeException
     * @inheritdoc
     */
    public function export(): void
    {
        $this->calculateProfiling();
        parent::export();
    }
}
