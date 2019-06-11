<?php
/**
 * @copyright Copyright (c) 2019
 */

return [
    'onRecordInsert' => [
        'class' => \yii\db\BaseActiveRecord::class,
        'name' => \yii\db\BaseActiveRecord::EVENT_AFTER_INSERT,
        'handler' => 'xxxHandler',
        'data' => ['123'],
        'append' => true,
    ]
];
