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
    方便管理AuthRole, 默认AuthPermission/AuthRule不需要修改编辑
    增加Menu类型Item,因为不是Permission类型，服务端权限验证不会加载，--待定
    Permission可分为Access/Result对应控制器权限，数据过滤
    Permission命名规则，app_module_controller_action 页面对应tab/card/row/checkbox
    Form保存模型关系处理，依旧依赖AuthManager
    Auth数据可跟随项目管理，也可统一管理，Rule必须跟随项目，关联Rule的Permission也得跟随

