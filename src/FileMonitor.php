<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Workerman\Lib\Timer;
use Workerman\Worker;

/**
 * Class FileMonitorWorker
 * @package lujie\workerman
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileMonitor
{
    private $lastModifyTime = 0;

    public $monitorDirs;

    public $checkInterval = 2;

    /**
     * @inheritdoc
     */
    public function startFileMonitoring(): void
    {
        $this->lastModifyTime = time();
        Timer::add($this->checkInterval, function () {
            foreach ($this->monitorDirs as $monitorDir) {
                if ($this->isFilesChanged($monitorDir)) {
                    $this->lastModifyTime = time();
                    posix_kill(posix_getppid(), SIGUSR1);
                    break;
                }
            }
        });
    }

    /**
     * @param string $dir
     * @inheritdoc
     */
    public function isFilesChanged(string $dir): bool
    {
        $directoryIterator = new RecursiveDirectoryIterator($dir);
        $fileIterator = new RecursiveIteratorIterator($directoryIterator);
        /** @var SplFileInfo $file */
        foreach ($fileIterator as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
                continue;
            }
            if ($this->lastModifyTime < $file->getMTime()) {
                Worker::safeEcho($file . " updated and reload\n");
                return true;
            }
        }
        return false;
    }
}
