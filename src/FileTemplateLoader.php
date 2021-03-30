<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document;

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
     * @param int|mixed|string $key
     * @return string
     * @inheritdoc
     */
    public function get($key): string
    {
        return file_get_contents(Yii::getAlias($this->filePath));
    }
}
