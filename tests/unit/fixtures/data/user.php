<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\extend\constants\StatusConst;

//$passwordHash = Yii::$app->security->generatePasswordHash('pass123');
$passwordHash = '$2y$13$nvuqMzhnJg9KbRejwwqQcOF7.MkLGIYQeAPkPeFoq8v.zWN0QGJaK';
return [
    [
        'user_id' => 1,
        'username' => 'test_user1',
        'auth_key' => 'xxxx',
        'password_hash' => $passwordHash,
        'email' => 'test1@xxx.com',
        'status' => StatusConst::STATUS_ACTIVE,
    ],
    [
        'user_id' => 2,
        'username' => 'test_user2',
        'auth_key' => 'xxxx',
        'password_hash' => $passwordHash,
        'email' => 'test2@xxx.com',
        'status' => StatusConst::STATUS_INACTIVE,
    ]
];
