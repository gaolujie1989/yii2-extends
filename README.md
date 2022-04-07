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

## Change RBAC tables
    方便管理AuthRole,
    默认AuthPermission/AuthRule不需要修改编辑
    增加Menu类型Item,因为不是Permission类型，服务端权限验证不会加载，--待定
    Form保存模型关系处理，依旧依赖AuthManager
    Auth数据跟随项目还是统一管理？？Rule必须跟随项目，关联Rule的Permission也没跟随,那Role呢
