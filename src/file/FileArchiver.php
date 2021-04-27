<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file;

use creocoder\flysystem\Filesystem;
use lujie\extend\helpers\ZipHelper;
use Yii;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class FileLogArchiver
 * @package lujie\extend\log
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileArchiver extends BaseObject
{
    /**
     * @var string|array
     */
    public $filesystem = 'filesystem';

    /**
     * @var string
     */
    public $archivePath = 'logs/';

    /**
     * @var array
     */
    public $logPathPatterns = [
        '@backend/runtime/logs/*.log.?',
        '@console/runtime/logs/*.log.?',
        '@frontend/runtime/logs/*.log.?',
    ];

    /**
     * @var callable, return two names, zip file path and local file name in zip
     */
    public $namesCallback = 'logFileNames';

    /**
     * @var bool
     */
    public $removeArchived = true;

    /**
     * @return Filesystem|object
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getFilesystem(): ?Filesystem
    {
        return $this->filesystem ? Instance::ensure($this->filesystem, Filesystem::class) : null;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function archive(): void
    {
        $files = $this->findFiles();
        foreach ($files as $file) {
            if (!filesize($file)) {
                unlink($file);
                continue;
            }
            if (is_callable($this->namesCallback)) {
                [$zipFilePath, $localName] = call_user_func($this->namesCallback, $file);
            } else if (is_string($this->namesCallback) && $this->hasMethod($this->namesCallback)) {
                [$zipFilePath, $localName] = $this->{$this->namesCallback}($file);
            } else {
                $pathInfo = pathinfo($file);
                $localName = $pathInfo['basename'];
                $zipFilePath = $pathInfo['dirname']  . '/' . $pathInfo['filename'] . '.zip';
            }
            Yii::info("Zip log file {$file} to {$zipFilePath}", __METHOD__);
            ZipHelper::writeZip($zipFilePath, [$localName => $file]);
            if ($this->removeArchived) {
                unlink($file);
            }
            $filesystem = $this->getFilesystem();
            if ($filesystem) {
                $zipFileName = pathinfo($zipFilePath, PATHINFO_BASENAME);
                $archivePath = $this->archivePath . $zipFileName;
                Yii::info("Archive log file {$zipFileName} to fs:{$archivePath}", __METHOD__);
                if ($filesystem->has($archivePath)) {
                    $filesystem->put($archivePath, file_get_contents($zipFilePath));
                } else {
                    $filesystem->write($archivePath, file_get_contents($zipFilePath));
                }
                unlink($zipFilePath);
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function findFiles(): array
    {
        $logFiles = [];
        foreach ($this->logPathPatterns as $logPathPattern) {
            $logFiles[] = glob(Yii::getAlias($logPathPattern));
        }
        $logFileCount = count($logFiles);
        Yii::info("Find {$logFileCount} files", __METHOD__);
        return array_unique(array_merge(...$logFiles));
    }

    /**
     * @param string $file
     * @return string|null
     * @inheritdoc
     */
    protected function readFileFirstLine(string $file): ?string
    {
        if (($resource = fopen($file, 'rb')) !== false) {
            $line = fgets($resource);
            fclose($resource);
            return $line ?: null;
        }
        return null;
    }

    /**
     * @param string $file
     * @return string[]
     * @inheritdoc
     */
    public function logFileNames(string $file): array
    {
        if ($firstLine = $this->readFileFirstLine($file)) {
            $startAt = strtotime(substr($firstLine, 0, 19));
        } else {
            $startAt = time();
        }
        while (is_numeric(substr($file, -1))) {
            $file = substr($file, 0, strrpos($file, '.'));
        }
        $pathInfo = pathinfo($file);
        if (preg_match('/\/(\w+)\/runtime\//', $file, $matches)) {
            $prefix = $matches[1] . '-';
        } else {
            $prefix = '';
        }
        $localFileName = $prefix . $pathInfo['filename'] . '-' . date('ymdHi', $startAt) . '.' . $pathInfo['extension'];
        $zipFilePath = $pathInfo['dirname']  . '/' . $prefix . $pathInfo['filename'] . '-' . date('ymdHi', $startAt) . '.zip';
        return [$zipFilePath, $localFileName];
    }
}