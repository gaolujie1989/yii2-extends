<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\web;


class UploadedFile extends \yii\web\UploadedFile
{
    /**
     * @var string
     */
    public $fileData;

    private static $_files;

    /**
     * @param string $file
     * @param bool $deleteTempFile
     * @return bool
     * @inheritdoc
     */
    public function saveAs($file, $deleteTempFile = true): bool
    {
        if ($this->error === UPLOAD_ERR_OK) {
            return file_put_contents($file, $this->fileData);
        }

        return false;
    }
}
