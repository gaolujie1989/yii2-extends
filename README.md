# yii2-auth

#### Introduction
Yii auth, extend yii2 filters and access checker
set Yii2 app behavior, can not config in main.php, because ActionAccessRule not included
```
'as authAccessControl' => [
    'class' => AccessControl::class,
    'rules' => [
        'actionAccessRule' => [
            'class' => ActionAccessRule::class,
            'controllers' => 'xxx/*',
            'allow' => true,
        ]
    ],
    'except' => ['gii/*', 'site/*', 'user/*', '*/options'],
],
```

