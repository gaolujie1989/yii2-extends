<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\flysystem;

use League\Flysystem\DirectoryListing;
use League\Flysystem\Filesystem as LeagueFilesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use yii\base\Component;

/**
 * Class Filesystem
 * @package lujie\extend\flysystem
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class Filesystem extends Component implements FilesystemOperator
{
    /**
     * @var array
     */
    public $config = [];

    /**
     * @var LeagueFilesystem
     */
    protected $filesystem;

    /**
     * @var FilesystemAdapter
     */
    protected $adapter;


    /**
     * @var string
     */
    public $cdn = '';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->adapter = $this->prepareAdapter();
        $this->filesystem = new LeagueFilesystem($this->adapter, $this->config);
    }

    /**
     * @return FilesystemAdapter
     * @inheritdoc
     */
    abstract protected function prepareAdapter(): FilesystemAdapter;

    /**
     * @param string $name
     * @param array $params
     * @return mixed
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        return call_user_func_array([$this->filesystem, $name], $params);
    }

    /**
     * @return LeagueFilesystem
     * @inheritdoc
     */
    public function getFilesystem(): LeagueFilesystem
    {
        return $this->filesystem;
    }

    /**
     * @return FilesystemAdapter
     * @inheritdoc
     */
    public function getFilesystemAdapter(): FilesystemAdapter
    {
        return $this->adapter;
    }

    /**
     * @param string $path
     * @param array $config
     * @return string
     * @inheritdoc
     */
    public function publicUrl(string $path, array $config = []): string
    {
        return rtrim($this->cdn, '/') . '/' . ltrim($path, '/');
    }

    /**
     * @param string $path
     * @param int $width
     * @param int $height
     * @param array $config
     * @return string
     * @inheritdoc
     */
    public function thumbnailUrl(string $path, int $width, int $height, array $config = []): string
    {
        return $this->publicUrl($path, $config);
    }

    #region FilesystemReader

    public function fileExists(string $location): bool
    {
        return $this->filesystem->fileExists($location);
    }

    public function directoryExists(string $location): bool
    {
        return $this->filesystem->directoryExists($location);
    }

    public function has(string $location): bool
    {
        return $this->filesystem->has($location);
    }

    public function read(string $location): string
    {
        return $this->filesystem->read($location);
    }

    public function readStream(string $location)
    {
        return $this->filesystem->readStream($location);
    }

    public function listContents(string $location, bool $deep = self::LIST_SHALLOW): DirectoryListing
    {
        return $this->filesystem->listContents($location, $deep);
    }

    public function lastModified(string $path): int
    {
        return $this->filesystem->lastModified($path);
    }

    public function fileSize(string $path): int
    {
        return $this->filesystem->fileSize($path);
    }

    public function mimeType(string $path): string
    {
        return $this->filesystem->mimeType($path);
    }

    public function visibility(string $path): string
    {
        return $this->filesystem->visibility($path);
    }

    #endregion

    #region FilesystemWriter

    public function write(string $location, string $contents, array $config = []): void
    {
        $this->filesystem->write($location, $contents, $config);
    }

    public function writeStream(string $location, $contents, array $config = []): void
    {
        $this->filesystem->writeStream($location, $contents, $config);
    }

    public function setVisibility(string $path, string $visibility): void
    {
        $this->filesystem->setVisibility($path, $visibility);
    }

    public function delete(string $location): void
    {
        $this->filesystem->delete($location);
    }

    public function deleteDirectory(string $location): void
    {
        $this->filesystem->deleteDirectory($location);
    }

    public function createDirectory(string $location, array $config = []): void
    {
        $this->filesystem->createDirectory($location, $config);
    }

    public function move(string $source, string $destination, array $config = []): void
    {
        $this->filesystem->move($source, $destination, $config);
    }

    public function copy(string $source, string $destination, array $config = []): void
    {
        $this->filesystem->copy($source, $destination, $config);
    }

    #endregion

    #region V1 Compatibility

    /**
     * @param string $path
     * @return array|string[]
     * @throws FilesystemException
     * @deprecated will remove later
     */
    public function getMetadata(string $path): array
    {
        $fileExists = $this->fileExists($path);
        if (!$fileExists) {
            $directoryExists = $this->directoryExists($path);
            return $directoryExists ? ['type' => 'directory'] : [];
        }
        return [
            'type' => 'file',
            'timestamp' => $this->lastModified($path),
            'size' => $this->fileSize($path),
            'mimeType' => $this->mimeType($path),
        ];
    }

    /**
     * @param string $path
     * @return int
     * @throws FilesystemException
     * @deprecated Use `fileSize` instead
     */
    public function getSize(string $path): int
    {
        return $this->fileSize($path);
    }

    /**
     * @param string $path
     * @return string
     * @throws FilesystemException
     * @deprecated Use `mimeType` instead
     */
    public function getMimetype(string $path): string
    {
        return $this->mimeType($path);
    }

    /**
     * @param string $path
     * @return int
     * @throws FilesystemException
     * @deprecated Use `lastModified` instead
     */
    public function getTimestamp(string $path): int
    {
        return $this->lastModified($path);
    }

    /**
     * @param string $path
     * @return string
     * @throws FilesystemException
     * @deprecated Use `visibility` instead
     */
    public function getVisibility(string $path): string
    {
        return $this->visibility($path);
    }

    /**
     * @param string $path
     * @param string $contents
     * @param array $config
     * @throws FilesystemException
     * @deprecated Use `write` instead
     */
    public function update(string $path, string $contents, array $config = []): void
    {
        $this->write($path, $contents, $config);
    }

    /**
     * @param string $path
     * @param $resource
     * @param array $config
     * @throws FilesystemException
     * @deprecated Use `writeStream` instead
     */
    public function updateStream(string $path, $resource, array $config = []): void
    {
        $this->writeStream($path, $resource, $config);
    }

    /**
     * @param string $path
     * @param string $newPath
     * @throws FilesystemException
     * @deprecated Use `move` instead
     */
    public function rename(string $path, string $newPath): void
    {
        $this->move($path, $newPath);
    }


    /**
     * @param string $dirname
     * @throws FilesystemException
     * @deprecated Use `deleteDirectory` instead
     */
    public function deleteDir(string $dirname): void
    {
        $this->deleteDirectory($dirname);
    }

    /**
     * @param string $dirname
     * @param array $config
     * @throws FilesystemException
     * @deprecated  Use `createDirectory` instead
     */
    public function createDir(string $dirname, array $config = []): void
    {
        $this->createDirectory($dirname, $config);
    }

    /**
     * @param string $path
     * @param string $contents
     * @param array $config
     * @throws FilesystemException
     * @deprecated  Use `write` instead
     */
    public function put(string $path, string $contents, array $config = []): void
    {
        $this->write($path, $contents, $config);
    }

    /**
     * @param string $path
     * @param $resource
     * @param array $config
     * @throws FilesystemException
     * @deprecated Use `writeStream` instead
     */
    public function putStream(string $path, $resource, array $config = []): void
    {
        $this->writeStream($path, $resource, $config);
    }

    /**
     * @param string $path
     * @return string
     * @throws FilesystemException
     * @deprecated will remove later
     */
    public function readAndDelete(string $path): string
    {
        try {
            return $this->read($path);
        } finally {
            $this->delete($path);
        }
    }

    #endregion
}
