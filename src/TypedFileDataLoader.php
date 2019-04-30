<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

/**
 * Class TypedFileDataLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TypedFileDataLoader extends FileDataLoader
{
    /**
     * @var string
     */
    public $typedFilePathTemplate = '{filePool}/{type}.php';

    /**
     * @param int|string $key
     * @return array|mixed|null
     * @inheritdoc
     */
    public function get($key)
    {
        if (empty($this->data[$key])) {
            $this->filePathTemplate = strtr($this->typedFilePathTemplate, ['{type}' => $key]);
            $this->data[$key] = $this->loadFilesData();
        }
        return $this->data[$key];
    }

    /**
     * @return array|mixed|void|null
     * @inheritdoc
     */
    public function all()
    {
        return $this->get('*');
    }
}
