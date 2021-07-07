<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\controllers\rest;

use creocoder\flysystem\Filesystem;
use lujie\extend\rest\MethodAction;
use lujie\upload\actions\FsFileDownloadAction;
use lujie\upload\actions\UploadAction;
use lujie\upload\forms\FileActionForm;
use lujie\upload\forms\UploadForm;
use Yii;
use yii\di\Instance;
use yii\rest\Controller;

/**
 * Class FileManagerController
 * @package backend\controllers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileController extends Controller
{
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
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'upload' => [
                'class' => UploadAction::class,
                'modelClass' => FileActionForm::class,
                'uploadModel' => [
                    'class' => UploadForm::class,
                    'fs' => $this->fs,
                    'fileNameTemplate' => '{name}.{ext}',
                    'allowedExtensions' => [],
                ]
            ],
            'move' => [
                'class' => MethodAction::class,
                'modelClass' => FileActionForm::class,
                'fs' => $this->fs,
                'method' => 'move',
            ],
            'copy' => [
                'class' => MethodAction::class,
                'modelClass' => FileActionForm::class,
                'fs' => $this->fs,
                'method' => 'copy',
            ],
            'delete' => [
                'class' => MethodAction::class,
                'modelClass' => FileActionForm::class,
                'fs' => $this->fs,
                'method' => 'delete',
            ],
            'download' => [
                'class' => FsFileDownloadAction::class,
                'checkAccess' => [$this, 'checkAccess'],
                'fs' => $this->fs
            ]
        ]);
    }

    /**
     * @param string $path
     * @param string $type ,  file or dir
     * @return array
     * @inheritdoc
     */
    public function actionList(string $path = '', string $type = ''): array
    {
        $listContents = $this->fs->listContents($path);
        if (empty($type)) {
            return $listContents;
        }
        return array_filter($listContents, static function ($content) use ($type) {
            return $content['type'] === $type;
        });
    }

    /**
     * @param string $path
     * @return array
     * @inheritdoc
     */
    public function actionDetail(string $path = ''): array
    {
        if (!$this->fs->has($path)) {
            return [];
        }
        return $this->fs->getMetadata($path);
    }
}
