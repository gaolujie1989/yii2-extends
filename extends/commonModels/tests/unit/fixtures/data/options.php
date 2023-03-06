<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\common\option\models\Option;

return [
    'optionType' => [
        'optionType' => 'Option Type',
        'optionValueType' => 'Option Value Type',
        'optionTag' => 'Option Tag',
    ],
    'optionValueType' => [
        'STRING' => ['name'=>'STRING', 'value' => Option::VALUE_TYPE_STRING],
        'INT' => ['name'=>'INTEGER', 'value' => Option::VALUE_TYPE_INT],
        'FLOAT' => ['name'=>'FLOAT', 'value' => Option::VALUE_TYPE_FLOAT],
    ],
    'optionTag' => [
        'primary' => ['name' => 'Primary', 'tag' => 'primary' ],
        'info' => ['name' => 'Info', 'tag' => 'info' ],
        'success' => ['name' => 'Success', 'tag' => 'success' ],
        'warning' => ['name' => 'Warning', 'tag' => 'warning' ],
        'danger' => ['name' => 'Danger', 'tag' => 'danger' ],
    ]
];