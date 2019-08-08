<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\behaviors;

function is_uploaded_file($filename)
{
    return true;
}

function move_uploaded_file ($filename, $destination)
{
    return rename($filename, $destination);
}


namespace yii\web;

function is_uploaded_file($filename)
{
    return true;
}

function move_uploaded_file ($filename, $destination)
{
    return rename($filename, $destination);
}
