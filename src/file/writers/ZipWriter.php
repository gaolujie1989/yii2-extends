<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\writers;

use lujie\extend\file\FileWriterInterface;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\helpers\FileHelper;
use ZipArchive;

/**
 * Class ZipWriter
 * @package extend\src\file\writers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ZipWriter extends BaseObject implements FileWriterInterface
{
    /**
     * @var ?int
     */
    public $zipFlags = ZipArchive::CREATE | ZipArchive::OVERWRITE;

    /**
     * @var array
     */
    public $errorMessages = [
        ZipArchive::ER_EXISTS => 'File already exists.',
        ZipArchive::ER_INCONS => 'Zip archive inconsistent.',
        ZipArchive::ER_MEMORY => 'Malloc failure.',
        ZipArchive::ER_NOENT => 'No such file.',
        ZipArchive::ER_NOZIP => 'Not a zip archive.',
        ZipArchive::ER_OPEN => 'Can\'t open file.',
        ZipArchive::ER_READ => 'Read error.',
        ZipArchive::ER_SEEK => 'Seek error.',
    ];

    /**
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    public function write(string $file, array $data): void
    {
        $zipArchive = new ZipArchive();
        if (($errorCode = $zipArchive->open($file, $this->zipFlags)) !== true) {
            $error = $this->errorMessages[$errorCode] ?? "Unknown (Code {$errorCode})";
            throw new InvalidArgumentException("Create zip file {$file} failed with error: {$error}");
        }
        foreach ($data as $localName => $fileOrDir) {
            if (is_int($localName)) {
                $localName = pathinfo($fileOrDir, PATHINFO_BASENAME);
            }
            if (is_dir($fileOrDir)) {
                $dirFiles = FileHelper::findFiles($fileOrDir);
                foreach ($dirFiles as $dirFile) {
                    $localFileName = $localName . substr($dirFile, strlen($fileOrDir));
                    $zipArchive->addFile($dirFile, $localFileName);
                }
            } elseif (is_file($fileOrDir)) {
                $zipArchive->addFile($fileOrDir, $localName);
            } else {
                throw new InvalidArgumentException("File or dir {$fileOrDir} not exist");
            }
        }
        $zipArchive->close();
    }
}
