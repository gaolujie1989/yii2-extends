<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\loaders;

use lujie\data\loader\BaseDataLoader;
use Yii;

/**
 * Class FileTemplateLoader
 * @package lujie\template\document
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileTemplateLoader extends BaseDataLoader
{
    /**
     * @var string
     */
    public $filePath;

    /**
     * @var bool
     */
    public $returnPath = false;

    /**
     * @param int|mixed|string $key
     * @return string
     * @inheritdoc
     */
    public function get($key): string
    {
        $filePath = Yii::getAlias($this->filePath);
        return $this->returnPath ? $filePath : file_get_contents($filePath);
    }
}
