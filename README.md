# yii2-auth

### 权限模块设计

- 配置在main.php，以behavior的形式全局控制，配合user的access checker

    ```php
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
    'as authResultControl' => [
          'class' => ResultControl::class,
          'rules' => [
              'actionResultRule' => [
                  'class' => ActionResultRule::class,
                  'controllers' => 'xxx/*',
                  'allow' => true,
              ],
          ],
          'except' => ['gii/*', 'debug/*', 'site/*', 'user/*', '*/options'],
      ],
    ```

- 为了兼容Yii2本身的AuthManager，数据表结构不做修改
- Form保存模型依旧依赖AuthManager保存，不使用ActiveRecord的Save
- 方便管理AuthRole,，默认AuthPermission/AuthRule不需要修改编辑，使用命令行同步
- Rule必须跟随项目，因为代码执行相关，关联Rule的Permission也得跟随
- 后台Permission可分为Access控制器权限/Result数据过滤权限，Permission定义
    - Access权限：app_module_controller_action
    - Result权限：app_module_controller_action_result
- 前台Permission可分为Menu菜单/Block页面内部块/Button按钮操作，Permission定义
    - Menu权限：app_module_route
    - Block权限：app_module_route_block
    - Button权限：app_module_controller_action直接用Access权限
- 前台Permission会不会和后台Permission相同导致重复，前台Permission加前缀？
- 前台Permission类型单独保存？服务端权限验证不需要加载？？