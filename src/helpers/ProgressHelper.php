<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use yii\db\Query;
use yii\di\Instance;
use yii\helpers\Console;

/**
 * Class ProgressHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ProgressHelper
{
    protected static $progressStart;
    protected static $progressEnd;
    protected static $progressPrefix = '';
    protected static $progressEta;
    protected static $progressEtaLastDone = 0;
    protected static $progressEtaLastUpdate;

    protected static $progressDone = 0;
    protected static $progressTotal = 1;

    protected static $currentUser;

    protected static $progressKey;
    public static $storageKey = 'progress';
    public static $storageService = 'redis';

    /**
     * @param int $done
     * @param int $total
     * @param string|null $prefix
     * @param string|null $key
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public static function start(int $done, int $total, ?string $prefix = null, ?string $key = null): void
    {
        if (static::isUserConsole()) {
            Console::startProgress($done, $total, $prefix);
            return;
        }

        static::$progressKey = $key;
        if (static::$progressKey) {
            static::$progressStart = time();
            static::$progressDone = $done;
            static::$progressTotal = $total;
            if ($prefix !== null) {
                self::$progressPrefix = $prefix;
            }
            static::saveProgress($done, $total);
        }
    }

    /**
     * @param int $done
     * @param int $total
     * @param string|null $prefix
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public static function update(int $done, int $total, ?string $prefix = null): void
    {
        if (static::isUserConsole()) {
            Console::updateProgress($done, $total, $prefix);
            return;
        }
        if (static::$progressKey) {
            static::setETA($done, $total);
            static::$progressDone = $done;
            static::$progressTotal = $total;
            if ($prefix !== null) {
                self::$progressPrefix = $prefix;
            }
            static::saveProgress($done, $total);
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public static function end(): void
    {
        if (static::isUserConsole()) {
            Console::endProgress();
            return;
        }
        if (static::$progressKey) {
            static::$progressEnd = time();
            static::saveProgress(static::$progressDone, static::$progressTotal);
        }
    }

    /**
     * @return bool
     */
    public static function isUserConsole(): bool
    {
        if (empty(static::$currentUser)) {
            static::$currentUser = get_current_user();
        }
        return PHP_SAPI === 'cli' && strpos(static::$currentUser, 'www') === false;
    }

    /**
     * Calculate $_progressEta, $_progressEtaLastUpdate and $_progressEtaLastDone
     * @param int $done the number of items that are completed.
     * @param int $total the total value of items that are to be done.
     * @see updateProgress
     * @since 2.0.14
     */
    protected static function setETA(int $done, int $total): void
    {
        if ($done > $total || $done === 0) {
            self::$progressEta = null;
            self::$progressEtaLastUpdate = time();
            return;
        }

        if ($done < $total && (time() - self::$progressEtaLastUpdate > 1 && $done > self::$progressEtaLastDone)) {
            $rate = (time() - (self::$progressEtaLastUpdate ?: self::$progressStart)) / ($done - self::$progressEtaLastDone);
            self::$progressEta = $rate * ($total - $done);
            self::$progressEtaLastUpdate = time();
            self::$progressEtaLastDone = $done;
        }
    }

    /**
     * @param int $done
     * @param int $total
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    protected static function saveProgress(int $done, int $total): void
    {
        $percent = ($total === 0) ? 1 : $done / $total;
        $info = static::$progressPrefix . sprintf('%d%% (%d/%d)', $percent * 100, $done, $total);
        $info .= self::$progressEta === null ? ' ETA: n/a' : sprintf(' ETA: %d sec.', self::$progressEta);
        $progressData = [
            'startedAt' => static::$progressStart,
            'endedAt' => static::$progressEnd,
            'done' => $done,
            'total' => $total,
            'info' => $info,
        ];
        $db = Instance::ensure(static::$storageService);
        if ($db instanceof \yii\redis\Connection) {
            $db->hset(static::$storageKey, static::$progressKey, $progressData);
        }
        if ($db instanceof \yii\db\Connection) {
            $condition = ['key' => static::$progressKey];
            $query = (new Query())->from(static::$storageKey)->andWhere($condition);
            $command = $db->createCommand();
            if ($query->exists()) {
                $command->update(static::$storageKey, $progressData, $condition);
            } else {
                $command->insert(static::$storageKey, $progressData);
            }
            $command->execute();
        }
    }
}
