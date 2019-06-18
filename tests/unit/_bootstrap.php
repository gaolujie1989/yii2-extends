<?php
// Here you can initialize variables that will be available to your tests

namespace lujie\upload\behaviors;

function is_uploaded_file()
{
    return true;
}

function move_uploaded_file ($filename, $destination)
{
    return rename($filename, $destination);
}


namespace yii\web;

function is_uploaded_file()
{
    return true;
}

function move_uploaded_file ($filename, $destination)
{
    return rename($filename, $destination);
}
