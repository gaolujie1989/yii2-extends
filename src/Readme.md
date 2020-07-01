## History

记录Model变动
oldValue
newValue
diffValue

- 对于普通数据，int/string类型，diffValue = '{oldValue}' -> '{newValue}'  
- 对于状态数据，diffValue = '{oldValueText}' -> '{newValueText}'
- 对于多选数组数据，diffValue => ['added' => [...], 'modified' => [...], 'deleted' => [...]]
- 以上，attribute可以用 aaa.bbb.ccc记录关联模型数据变动

- 对于One-One 关联模型(ex. Order->Address)，
- 对于One-Many关联模型数据不更新(ex. Order-Attachments)，
- 对于One-Many关联模型数据更新(ex. Order-OrderItems)，
 
针对关联模型的变动记录
AttributeHistoryHandler
extract 提取要记录的数据，去掉不必要的数据
diff 比对记录数据，返回 added/deleted/modified 数据
