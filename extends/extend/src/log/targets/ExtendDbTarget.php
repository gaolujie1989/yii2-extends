<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\log\targets;

use BackupManager\Databases\PostgresqlDatabase;
use Yii;
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
    use LogContextMassageTrait;

    /**
     * @var array the messages that need to be profiled on duration bigger.
     */
    public $profilingOn = [
        'yii\db\Command::query' => 0.1,
        'yii\db\Command::execute' => 0.05,
        '*' => 1,
    ];

    /**
     * @throws Exception
     * @throws LogRuntimeException
     * @inheritdoc
     */
    public function export(): void
    {
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
                    ':duration' => 0,
                    ':prefix' => $this->getMessagePrefix($message),
                    ':message' => $text,
                    ':summary' => substr($this->getSummary($message), 0, 200),
                    ':memory_usage' => $message[5] ?? 0,
                    ':memory_diff' => 0,
                ])->execute() > 0) {
                continue;
            }

            throw new LogRuntimeException('Unable to export log through database!');
        }

        $calculateTimings = Yii::getLogger()->calculateTimings($this->messages);
        foreach ($calculateTimings as $timing) {
            if (!$this->isProfiling($timing)) {
                continue;
            }
            if ($command->bindValues([
                    ':level' => Logger::LEVEL_PROFILE,
                    ':category' => $timing['category'],
                    ':log_at' => $timing['timestamp'],
                    ':duration' => $timing['duration'],
                    ':prefix' => '',
                    ':message' => $timing['info'],
                    ':summary' => substr($timing['info'], 0, 200),
                    ':memory_usage' => $timing['memory'],
                    ':memory_diff' => $timing['memoryDiff'],
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
            return $text->getMessage();
        }
        if (is_array($text) && isset($text[0])) {
            return $text[0];
        }
        return '';
    }

    /**
     * @param array $timing
     * @return bool
     * @inheritdoc
     */
    private function isProfiling(array $timing): bool
    {
        if (empty($this->profilingOn)) {
            return true;
        }
        $category = $timing['category'];
        $duration = $timing['duration'];
        foreach ($this->profilingOn as $profilingCategory => $profilingDuration) {
            if ($category === $profilingCategory
                || (str_ends_with($profilingCategory, '*') && str_starts_with($category, rtrim($profilingCategory, '*')))) {
                return $duration >= $profilingDuration;
            }
        }
        return false;
    }
}
