<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use yii\helpers\ArrayHelper;

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
     * @var string
     */
    public $allType = '*';

    /**
     * @param int|string $key
     * @return array|mixed|null
     * @inheritdoc
     * @throws \Exception
     */
    public function get($key)
    {
        if (empty($this->data[$key])) {
            $this->filePathTemplate = strtr($this->typedFilePathTemplate, ['{type}' => $key]);
            $this->data = $this->loadFilesData();
        }
        return ArrayHelper::getValue($this->data, $key);
    }

    /**
     * @return array|null
     * @inheritdoc
     */
    public function all(): ?array
    {
        $this->filePathTemplate = strtr($this->typedFilePathTemplate, ['{type}' => $this->allType]);
        $this->data = $this->loadFilesData();
        return $this->data;
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function loadFilesData(): array
    {
        $loadedFiles = $this->findFiles();
        $data = [[], []];
        foreach ($loadedFiles as $loadedFile) {
            $fileName = pathinfo($loadedFile, PATHINFO_FILENAME);
            $data[] = [$fileName => $this->fileReader->read($loadedFile)];
        }
        return ArrayHelper::merge(...$data);
    }
}
