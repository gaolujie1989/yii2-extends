<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\behaviors;

use Workerman\Protocols\Http;

function is_uploaded_file($filename): bool
{
    return strpos($filename, Http::uploadTmpDir()) !== false
        && strpos($filename, 'workerman.upload.') !== false;
}

function move_uploaded_file($filename, $destination): bool
{
    if (is_uploaded_file($filename)) {
        return rename($filename, $destination);
    }
}


namespace yii\web;

use Workerman\Protocols\Http;

function is_uploaded_file($filename): bool
{
    return strpos($filename, Http::uploadTmpDir()) !== false
        && strpos($filename, 'workerman.upload.') !== false;
}

function move_uploaded_file($filename, $destination): bool
{
    if (is_uploaded_file($filename)) {
        return rename($filename, $destination);
    }
    return false;
}

function headers_sent(&$file = null, &$line = null): bool
{
    return false;
}
