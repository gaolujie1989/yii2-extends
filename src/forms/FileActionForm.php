<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\forms;

use lujie\extend\flysystem\Filesystem;
use yii\base\Model;
use yii\di\Instance;

/**
 * Class FileActionForm
 * @package lujie\upload\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileActionForm extends Model
{
    public const SCENARIO_MOVE_COPY = 'MOVE_COPY';

    public const SCENARIO_DELETE = 'VIEW_DELETE';

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $newPath;

    /**
     * @var Filesystem
     */
    public $fs = 'filesystem';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fs = Instance::ensure($this->fs, Filesystem::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_DEFAULT => ['path', 'newPath'],
            self::SCENARIO_MOVE_COPY => ['path', 'newPath'],
            self::SCENARIO_DELETE => ['path'],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['path', 'newPath'], 'required'],
            [['path', 'newPath'], 'string'],
            [['path'], 'validatePath'],
            [['newPath'], 'validateNewPath'],
        ];
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     * @inheritdoc
     */
    public function validatePath(): void
    {
        if (!$this->fs->has($this->path)) {
            $this->addError('path', 'Path not exists');
        }
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     * @inheritdoc
     */
    public function validateNewPath(): void
    {
        if (!$this->fs->has($this->path)) {
            $this->addError('newPath', 'New path already exists');
        }
    }

    /**
     * @param string $path
     * @return bool
     * @throws \League\Flysystem\FilesystemException
     * @inheritdoc
     */
    protected function isFile(string $path): bool
    {
        $metadata = $this->fs->getMetadata($path);
        return $metadata['type'] === 'file';
    }

    /**
     * @return bool
     * @throws \League\Flysystem\FilesystemException
     * @inheritdoc
     */
    public function move(): bool
    {
        $this->setScenario(self::SCENARIO_MOVE_COPY);
        if (!$this->validate()) {
            return false;
        }
        $this->fs->rename($this->path, $this->newPath);
        return true;
    }

    /**
     * @return bool
     * @throws \League\Flysystem\FilesystemException
     * @inheritdoc
     */
    public function copy(): bool
    {
        $this->setScenario(self::SCENARIO_MOVE_COPY);
        if (!$this->validate()) {
            return false;
        }
        $this->fs->copy($this->path, $this->newPath);
        return true;
    }

    /**
     * @return bool
     * @throws \League\Flysystem\FilesystemException
     * @inheritdoc
     */
    public function delete(): bool
    {
        $this->setScenario(self::SCENARIO_DELETE);
        if (!$this->validate()) {
            return false;
        }
        $this->isFile($this->path)
            ? $this->fs->delete($this->path)
            : $this->fs->deleteDir($this->path);
        return true;
    }
}
