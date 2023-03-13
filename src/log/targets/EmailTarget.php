<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\log\targets;

use yii\helpers\VarDumper;
use yii\log\Logger;

/**
 * Class EmailTarget
 * @package lujie\extend\log\targets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class EmailTarget extends \yii\log\EmailTarget
{
    public $appendMessageToSubject = true;

    public $subjectTemplate = '[{subject}] {text}';

    /**
     * @param string $body
     * @return \yii\mail\MessageInterface
     * @inheritdoc
     */
    protected function composeMessage($body): \yii\mail\MessageInterface
    {
        $message = parent::composeMessage($body);
        if ($this->appendMessageToSubject) {
            [$text, $level, $category, $timestamp] = $this->messages;
            $level = Logger::getLevelName($level);
            if (!is_string($text)) {
                if ($text instanceof \Throwable) {
                    $text = $text->getMessage();
                } else {
                    $text = VarDumper::export($text);
                }
            }

            $message->setSubject(strtr($this->subjectTemplate, [
                '{subject}' => $this->message['subject'],
                '{text}' => substr($text, 0, 220),
                '{level}' => $level,
                '{category}' => $category,
                '{timestamp}' => $timestamp,
            ]));
        }
        return $message;
    }
}