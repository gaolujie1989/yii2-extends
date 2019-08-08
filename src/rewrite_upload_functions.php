<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\behaviors;

function is_uploaded_file($filename)
{
    return strpos($filename, WORKERMAN_UPLOAD_FILENAME_PREFIX);
}

function move_uploaded_file($filename, $destination)
{
    if (is_uploaded_file($filename)) {
        return rename($filename, $destination);
    }
}


namespace yii\web;

function is_uploaded_file($filename)
{
    return strpos($filename, WORKERMAN_UPLOAD_FILENAME_PREFIX);
}

function move_uploaded_file($filename, $destination)
{
    if (is_uploaded_file($filename)) {
        return rename($filename, $destination);
    }
    return false;
}
