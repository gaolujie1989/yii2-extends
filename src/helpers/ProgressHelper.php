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
    private static $_progressStart;
    private static $_progressEnd;
    private static $_progressPrefix = '';
    private static $_progressEta;
    private static $_progressEtaLastDone = 0;
    private static $_progressEtaLastUpdate;

    private static $_progressDone = 0;
    private static $_progressTotal = 1;

    private static $_currentUser;

    private static $_progressKey;
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
    public static function start(int $done, int $total, ?string $prefix = null, ?string $key = null)
    {
        if (static::isUserConsole()) {
            Console::startProgress($done, $total, $prefix);
            return;
        }

        static::$_progressKey = $key;
        if (static::$_progressKey) {
            static::$_progressStart = time();
            static::$_progressDone = $done;
            static::$_progressTotal = $total;
            if ($prefix !== null) {
                self::$_progressPrefix = $prefix;
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
    public static function update(int $done, int $total, ?string $prefix = null)
    {
        if (static::isUserConsole()) {
            Console::updateProgress($done, $total, $prefix);
            return;
        }
        if (static::$_progressKey) {
            static::setETA($done, $total);
            static::$_progressDone = $done;
            static::$_progressTotal = $total;
            if ($prefix !== null) {
                self::$_progressPrefix = $prefix;
            }
            static::saveProgress($done, $total);
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public static function end()
    {
        if (static::isUserConsole()) {
            Console::endProgress();
            return;
        }
        if (static::$_progressKey) {
            static::$_progressEnd = time();
            static::saveProgress(static::$_progressDone, static::$_progressTotal);
        }
    }

    /**
     * @return bool
     */
    public static function isUserConsole(): bool
    {
        if (empty(static::$_currentUser)) {
            static::$_currentUser = get_current_user();
        }
        return PHP_SAPI === 'cli' && strpos(static::$_currentUser, 'www') === false;
    }

    /**
     * Calculate $_progressEta, $_progressEtaLastUpdate and $_progressEtaLastDone
     * @param int $done the number of items that are completed.
     * @param int $total the total value of items that are to be done.
     * @see updateProgress
     * @since 2.0.14
     */
    private static function setETA($done, $total)
    {
        if ($done > $total || $done == 0) {
            self::$_progressEta = null;
            self::$_progressEtaLastUpdate = time();
            return;
        }

        if ($done < $total && (time() - self::$_progressEtaLastUpdate > 1 && $done > self::$_progressEtaLastDone)) {
            $rate = (time() - (self::$_progressEtaLastUpdate ?: self::$_progressStart)) / ($done - self::$_progressEtaLastDone);
            self::$_progressEta = $rate * ($total - $done);
            self::$_progressEtaLastUpdate = time();
            self::$_progressEtaLastDone = $done;
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
        $percent = ($total == 0) ? 1 : $done / $total;
        $info = static::$_progressPrefix . sprintf('%d%% (%d/%d)', $percent * 100, $done, $total);
        $info .= self::$_progressEta === null ? ' ETA: n/a' : sprintf(' ETA: %d sec.', self::$_progressEta);
        $progressData = [
            'startedAt' => static::$_progressStart,
            'endedAt' => static::$_progressEnd,
            'done' => $done,
            'total' => $total,
            'info' => $info,
        ];
        $db = Instance::ensure(static::$storageService);
        if ($db instanceof \yii\redis\Connection) {
            $db->hset(static::$storageKey, static::$_progressKey, $progressData);
        }
        if ($db instanceof \yii\db\Connection) {
            $condition = ['key' => static::$_progressKey];
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
