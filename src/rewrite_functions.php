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

use Workerman\Protocols\Http;

function is_uploaded_file($filename)
{
    return strpos($filename, WORKERMAN_UPLOAD_FILENAME_PREFIX) !== false;
}

function move_uploaded_file($filename, $destination)
{
    if (is_uploaded_file($filename)) {
        return rename($filename, $destination);
    }
    return false;
}

function headers_sent(&$file = null, &$line = null)
{
    return false;
}

function header($string, $replace = true, $http_response_code = null)
{
    Http::header($string, $replace, $http_response_code);
}

function setcookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false)
{
    Http::setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
}
