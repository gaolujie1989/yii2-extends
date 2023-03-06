# yii2-extends

提供一些有用的Yii2扩展

### base extend
扩展yii2本身的一些功能

### AliasProperty
属性别名扩展，别名属性自动get/set, 可以转换，比如时间格式化和时间戳之间的转换，重量尺寸单位的别名的自动转化

### AmazonADClient/AmazonCPClient
Amazon AD和SP api client

### ArDeletedBackup
ActiveRecord删除备份，不是SoftDelete

### ArHistory
通过配置，实现特定的ActiveRecord中特定Attribute的变更历史记录

### ArSnapshot
记录ActiveRecord的变更快照

### User/Auth
扩展Yii2-authManager，在Application层全局控制权限，并可以通过数据库维护权限

### remoteUser
用户的登录验证通过其他服务器验证，并适配Yii2本身的User

### Charging
通过配置ChargeTable价格表和自定义Calculator计算器，实现配置话动态价格计算

### dataExchange / dataLoader / dataStorage
数据加载器/存储器/转换器，一般用于数据的导入导出，不通模块之间数据加载的隔离等

### dpdClient / glsClient
DPD/GLS快递SOAP API Client

### executing / scheduling
执行器，定时器，后台进程定时触发任务，执行任务，可队列异步执行，可加锁隔离运行，记录执行结果

### queuingMonitor
队列监控，查看queue job 和 worker的状态

### fulfillment
可对接各个发货仓库，推送发货产品，同步产品库存，推送发货订单，更新发货快递信息

### salesChannel
可对接各个销售平台，推送销售产品，同步销售库存，获取销售订单，推送订单发货信息

