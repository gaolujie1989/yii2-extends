<?php
/**
 * @copyright Copyright (c) 2019
 */

if (!function_exists('xxxSetParams')) {
    function xxxSetParams()
    {
        Yii::$app->params['xxxSetParams'] = 'executed';
    }
}

return [
    'xxxSetParams' => 'xxxSetParams'
];
