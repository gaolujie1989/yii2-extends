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

    public $monitorDir;

    public $checkInterval = 2;

    /**
     * @inheritdoc
     */
    public function startFileMonitoring(): void
    {
        $this->lastModifyTime = time();
        if(!Worker::$daemonize) {
            // check mtime of files every interval second
            Timer::add($this->checkInterval, [$this, 'checkFilesChange'], [$this->monitorDir]);
        }
    }

    /**
     * @param string $dir
     * @inheritdoc
     */
    public function checkFilesChange(string $dir): void
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
                posix_kill(posix_getppid(), SIGUSR1);
                $this->lastModifyTime = time();
            }
        }
    }
}
