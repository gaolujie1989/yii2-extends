<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\file;

use lujie\extend\flysystem\Filesystem;
use yii\di\Instance;

/**
 * Trait Base64ContentTrait
 *
 * @property Filesystem $fs
 * @property string $filePathTemplate
 * @property string $fileAttribute = 'file'
 * @property string $extAttribute = 'ext'
 * @property string $sizeAttribute = 'size'
 *
 * @package lujie\extend\file
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait Base64ContentFileTrait
{
    /**
     * @var string
     */
    public $base64_content;

    /**
     * @param array $filePathParams
     * @throws \League\Flysystem\FilesystemException
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function saveBase64ContentFile(array $filePathParams): void
    {
        if (empty($this->base64_content)) {
            return;
        }
        $fileAttribute = $this->fileAttribute ?? 'file';
        $sizeAttribute = $this->sizeAttribute ?? 'size';
        $extAttribute = $this->extAttribute ?? 'ext';
        $fileContent = base64_decode($this->base64_content);

        $filePathParams = array_merge([
            '{date}' => date('Y-m-d'),
            '{rand}' => random_int(1000, 9999),
        ], $filePathParams);
        $filePath = strtr($this->filePathTemplate, $filePathParams);
        $this->setAttribute($fileAttribute, $filePath);
        if ($this->hasAttribute($extAttribute)) {
            $this->setAttribute($extAttribute, strtolower(pathinfo($filePath, PATHINFO_EXTENSION)));
        }
        if ($this->hasAttribute($sizeAttribute)) {
            $this->setAttribute($sizeAttribute, strlen($fileContent));
        }

        $filesystem = Instance::ensure($this->fs ?? 'filesystem', Filesystem::class);
        $filesystem->write($filePath, $fileContent);
    }
}
