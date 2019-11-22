# yii2-charging

#### Introduction
Yii2 charging, by price rate

实现以下逻辑
ModelAB
在 status = 1 的时候 预估 A 的费用
在 status = 2 的时候 预估 B 的费用
在 status = 3 的时候 重新计算 AB 的费用

每个chargeType对应一个Model一张表，一条记录可对应多个chargeType，但是每个chargeType只有一个chargePrice
如果一个Model有不同的子类对应不同的计费，用subType区分，只能对应一个subType
chargeGroup只是后续统计用
