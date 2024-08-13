<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\log\targets;

use Yii;
use yii\log\Logger;

/**
 * Trait LogContextMassageTrait
 *
 * @property array $profilingOn; the messages that need to be profiled on duration bigger.
 *
 * @package lujie\extend\log\targets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait LogProfilingTrait
{
    protected function calculateProfiling(): void
    {
        $timings = Yii::getLogger()->calculateTimings($this->messages);
        foreach ($this->messages as $key => $message) {
            $level = $message[1];
            if ($level === Logger::LEVEL_PROFILE_BEGIN || $level === Logger::LEVEL_PROFILE_END) {
                unset($this->messages[$key]);
            }
        }
        foreach ($timings as $timing) {
            if (!$this->isProfiling($timing)) {
                continue;
            }
            $this->messages[] = [
                $timing['info'],
                Logger::LEVEL_PROFILE,
                $timing['category'],
                $timing['timestamp'],
                $timing['trace'],
                $timing['memory'],
                $timing['memoryDiff'],
                $timing['duration'],
            ];
        }
    }

    /**
     * @param array $timing
     * @return bool
     * @inheritdoc
     */
    protected function isProfiling(array $timing): bool
    {
        $profilingOn = $this->profilingOn ?? [
            'yii\db\Command::query' => 0.1,
            'yii\db\Command::execute' => 0.05,
            '*' => 1,
        ];
        if (!$profilingOn) {
            return true;
        }
        $category = $timing['category'];
        $duration = $timing['duration'];
        foreach ($profilingOn as $profilingCategory => $profilingDuration) {
            if ($category === $profilingCategory
                || (str_ends_with($profilingCategory, '*') && str_starts_with($category, rtrim($profilingCategory, '*')))) {
                return $duration >= $profilingDuration;
            }
        }
        return false;
    }
}
