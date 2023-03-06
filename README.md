# yii2-extends

Provides some useful Yii2 extensions

### base extend
Extends some of the functionality of yii2 itself

### AliasProperty
Alias property extensions, which automatically get/set alias properties, can be converted, e.g. between time formatting and timestamps, automatic conversion of aliases for weight and dimension units

### AmazonADClient/AmazonCPClient
Amazon AD and SP api client

### ArDeletedBackup
ActiveRecord deleted backups, not SoftDelete

### ArHistory
Configuration to enable change history for a specific Attribute in a specific ActiveRecord

### ArSnapshot
Record snapshots of changes to an ActiveRecord

### User/Auth
Extend Yii2-authManager to globally control permissions at the Application level and maintain them through the database

### remoteUser
User login authentication through other servers and adaptation to Yii2's own User

### Charging
Dynamic price calculation for configured sessions by configuring a ChargeTable price table and a custom Calculator calculator

### dataExchange / dataLoader / dataStorage
dataLoader / storage / converter, generally used for importing and exporting data, isolating data loading between non-modules, etc.

### dpdClient / glsClient
DPD / GLS Express SOAP API Client

### executing / scheduling
executor, timer, background process triggers tasks at regular intervals, executes tasks, can queue asynchronous execution, can be locked to isolate operation, records execution results

### queuingMonitor
Queue monitor, view the status of queue jobs and workers

### fulfillment
Dock to each shipping warehouse, push shipping products, synchronise product inventory, push shipping orders, update shipping courier information

### salesChannel
Docking to various sales platforms, pushing sales products, synchronising sales inventory, getting sales orders and pushing order dispatch information
