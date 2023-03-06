<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\file\FileReaderInterface;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\UserException;
use ZipArchive;

/**
 * Class ZipReader
 * @package lujie\extend\file\readers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ZipReader extends BaseObject implements FileReaderInterface
{
    /**
     * @var string
     */
    public $destination;

    /**
     * @var array
     */
    public $entries = [];

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
     * @return array
     * @throws UserException
     * @inheritdoc
     */
    public function read(string $file): array
    {
        $zipArchive = new ZipArchive();
        if (($errorCode = $zipArchive->open($file, ZipArchive::RDONLY)) !== true) {
            $error = $this->errorMessages[$errorCode] ?? "Unknown (Code {$errorCode})";
            throw new UserException("Create zip file {$file} failed with error: {$error}");
        }
        if ($this->entries) {
            $zipArchive->extractTo($this->destination, $this->entries);
        } else {
            $zipArchive->extractTo($this->destination);
        }
        $zipArchive->close();
        return $this->entries;
    }
}
