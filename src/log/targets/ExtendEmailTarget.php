<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\log\targets;

use lujie\extend\helpers\TemplateHelper;
use yii\helpers\VarDumper;
use yii\log\Logger;
use yii\log\LogRuntimeException;

/**
 * Class EmailTarget
 * @package lujie\extend\log\targets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExtendEmailTarget extends \yii\log\EmailTarget
{
    use LogContextMassageTrait;

    /**
     * @var string
     */
    public $subjectTemplate = '[{subject}][{level}][{category}][{summary}]';

    /**
     * @var bool
     */
    public $appendMessageToSubject = true;

    /**
     * @inheritdoc
     */
    public function export(): void
    {
        // moved initialization of subject here because of the following issue
        // https://github.com/yiisoft/yii2/issues/1446
        if (empty($this->message['subject'])) {
            $this->message['subject'] = 'Application Log';
        }
        $messages = array_map([$this, 'formatMessage'], $this->messages);
        $body = wordwrap(implode("\n", $messages), 70);
        $message = $this->composeMessage($body);
        if ($this->appendMessageToSubject) {
            $subject = $this->generateSubject(reset($this->messages));
            $message->setSubject($subject);
        }
        if (!$message->send($this->mailer)) {
            throw new LogRuntimeException('Unable to export log through email!');
        }
    }

    /**
     * @param array $message
     * @return string
     * @inheritdoc
     */
    private function generateSubject(array $message): string
    {
        [$text, $level, $category, $timestamp, $traces, $memoryUsage] = $message;
        $subjectData = [
            'subject' => $this->message['subject'],
            'datetime' => $this->getTime($timestamp),
            'prefix' => $this->getMessagePrefix($message),
            'level' => Logger::getLevelName($level),
            'category' => $category,
            'memory' => number_format($memoryUsage / 1024 / 1024, 2) . ' MB',
            'summary' => substr($this->getSummary($message), 0, 200),
        ];

        return TemplateHelper::render($this->subjectTemplate, $subjectData);
    }

    /**
     * @param array $message
     * @return string
     * @inheritdoc
     */
    private function getSummary(array $message): string
    {
        $text = $message[0];
        if (is_string($text)) {
            return $text;
        }
        if ($text instanceof \Throwable) {
            return $text->getMessage();
        }
        if (is_array($text) && isset($text[0])) {
            return $text[0];
        }
    }
}
