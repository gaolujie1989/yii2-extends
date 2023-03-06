<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\jobs;

use lujie\data\exchange\FileImporter;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\queue\JobInterface;

/**
 * Class FileImportJob
 * @package lujie\data\exchange\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileImportJob extends BaseObject implements JobInterface
{
    /**
     * @var string
     */
    public $file;

    /**
     * @var FileImporter
     */
    public $fileImporter;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function execute($queue)
    {
        $this->fileImporter = Instance::ensure($this->fileImporter, FileImporter::class);
        $this->fileImporter->import($this->file);
    }
}
