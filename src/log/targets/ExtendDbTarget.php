<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\log\targets;

use yii\db\Exception;
use yii\helpers\VarDumper;
use yii\log\Logger;
use yii\log\LogRuntimeException;

/**
 * Class CustomDbTarget
 * @package lujie\extend\log\targets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExtendDbTarget extends \yii\log\DbTarget
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
     * @throws Exception
     * @throws LogRuntimeException
     * @inheritdoc
     */
    public function export(): void
    {
        $this->calculateProfiling();
        if ($this->db->getTransaction()) {
            // create new database connection, if there is an open transaction
            // to ensure insert statement is not affected by a rollback
            $this->db = clone $this->db;
        }

        $tableName = $this->db->quoteTableName($this->logTable);
        $sql = "INSERT INTO $tableName ([[level]], [[category]], [[log_at]], [[duration]], [[prefix]], [[message]], [[summary]], [[memory_usage]], [[memory_diff]])
                VALUES (:level, :category, :log_at, :duration, :prefix, :message, :summary, :memory_usage, :memory_diff)";
        $command = $this->db->createCommand($sql);
        foreach ($this->messages as $message) {
            [$text, $level, $category, $timestamp] = $message;
            if ($level === Logger::LEVEL_PROFILE_BEGIN || $level === Logger::LEVEL_PROFILE_END) {
                continue;
            }
            if (!is_string($text)) {
                // exceptions may not be serializable if in the call stack somewhere is a Closure
                if ($text instanceof \Throwable) {
                    $text = (string)$text;
                } else {
                    $text = VarDumper::export($text);
                }
            }
            if ($command->bindValues([
                    ':level' => $level,
                    ':category' => $category,
                    ':log_at' => $timestamp,
                    ':prefix' => $this->getMessagePrefix($message),
                    ':message' => $text,
                    ':summary' => substr($this->getSummary($message), 0, 200),
                    ':memory_usage' => $message[5] ?? 0,
                    ':memory_diff' => $message[6] ?? 0,
                    ':duration' => $message[7] ?? 0,
                ])->execute() > 0) {
                continue;
            }

            throw new LogRuntimeException('Unable to export log through database!');
        }
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
            return $text->getMessage() ?: '';
        }
        if (is_array($text) && isset($text[0])) {
            return $text[0];
        }
        return '';
    }
}
